<?php

namespace App\Services\Frontend;

use App\Models\Playlist;
use App\Models\Video;
use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaylistService
{
    public function index(): array
    {
        $playlists = auth()->user()->playlists()
            ->withCount(['videos', 'reels'])
            ->with(['videos' => function($q) {
                $q->forUser()->limit(1);
            }])
            ->latest()
            ->get();

        return compact('playlists');
    }

    public function show($username, Playlist $playlist): array
    {
        $playlist->load([
            'videos' => function($q) { $q->forUser()->with('user.channel'); },
            'reels' => function($q) { $q->forUser()->with('user.channel'); }
        ]);

        return compact('playlist');
    }

    public function store(Request $request): array
    {
        $request->validate(['name' => 'required|string|max:255']);

        $playlist = auth()->user()->playlists()->create([
            'name' => $request->name
        ]);

        return ['playlist' => $playlist];
    }

    public function toggleVideo(Request $request, Video $video): array
    {
        $user = auth()->user();
        $playlistId = $request->playlist_id;

        session_write_close();

        $exists = DB::table('playlists')->where('id', $playlistId)->where('user_id', $user->id)->exists();
        if (!$exists) abort(403);

        $pivot = DB::table('playlist_video')
            ->where('playlist_id', $playlistId)
            ->where('video_id', $video->id);

        if ($pivot->exists()) {
            $pivot->delete();
            $added = false;
        } else {
            DB::table('playlist_video')->insert([
                'playlist_id' => $playlistId,
                'video_id' => $video->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $added = true;
        }

        \Log::info("User {$user->id} toggled video {$video->id} in playlist {$playlistId}. Added: " . ($added ? 'yes' : 'no'));

        return [
            'status' => $added ? 'added' : 'removed',
            'message' => $added ? 'Added to playlist' : 'Removed from playlist'
        ];
    }

    public function toggleReel(Request $request, $reelId): array
    {
        $reel = Reel::where('id', $reelId)->orWhere('slug', $reelId)->firstOrFail();
        $user = auth()->user();
        $playlistId = $request->playlist_id;

        session_write_close();

        $exists = DB::table('playlists')->where('id', $playlistId)->where('user_id', $user->id)->exists();
        if (!$exists) abort(403);

        $pivot = DB::table('playlist_reel')
            ->where('playlist_id', $playlistId)
            ->where('reel_id', $reel->id);

        if ($pivot->exists()) {
            $pivot->delete();
            $added = false;
        } else {
            DB::table('playlist_reel')->insert([
                'playlist_id' => $playlistId,
                'reel_id' => $reel->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $added = true;
        }

        return [
            'status' => $added ? 'added' : 'removed',
            'message' => $added ? 'Added to playlist' : 'Removed from playlist'
        ];
    }

    public function watchLater(Request $request, $id): array
    {
        $type = $request->type ?? 'video';

        session_write_close();

        if ($type === 'reel') {
            $item = Reel::where('id', $id)->first() ?? Reel::where('slug', $id)->first();
            $pivotRelation = 'reels';
        } else {
            $item = Video::where('id', $id)->first() ?? Video::where('slug', $id)->first();
            $pivotRelation = 'videos';
        }

        if (!$item) {
            return [
                'status' => 'error',
                'message' => ucfirst($type) . ' not found or has been deleted.'
            ];
        }

        $playlists = auth()->user()->playlists()
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Watch Later%')
                  ->orWhere('name', 'LIKE', '%watch later%');
            })
            ->get();

        if ($playlists->isEmpty()) {
            $defaultPlaylist = auth()->user()->playlists()->create(['name' => 'Watch Later']);
            $playlists = collect([$defaultPlaylist]);
        }

        $existsInAny = false;
        foreach ($playlists as $playlist) {
            if ($playlist->$pivotRelation()->where($type . '_id', $item->id)->exists()) {
                $existsInAny = true;
                break;
            }
        }

        if ($existsInAny) {
            foreach ($playlists as $playlist) {
                $playlist->$pivotRelation()->detach($item->id);
            }
            $added = false;
        } else {
            $playlists->first()->$pivotRelation()->attach($item->id);
            $added = true;
        }

        return [
            'status' => $added ? 'added' : 'removed',
            'message' => $added ? 'Saved to Watch Later' : 'Removed from Watch Later'
        ];
    }

    /**
     * Idempotent remove-from-Watch-Later (detach-only, never re-adds).
     * Safe under rapid repeat clicks: N calls always end at "removed".
     */
    public function removeWatchLater(Request $request, $id): array
    {
        $type = $request->type ?? 'video';

        session_write_close();

        if ($type === 'reel') {
            $item = Reel::where('id', $id)->first() ?? Reel::where('slug', $id)->first();
            $pivotRelation = 'reels';
        } else {
            $item = Video::where('id', $id)->first() ?? Video::where('slug', $id)->first();
            $pivotRelation = 'videos';
        }

        $playlists = auth()->user()->playlists()
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Watch Later%')
                  ->orWhere('name', 'LIKE', '%watch later%');
            })
            ->get();

        foreach ($playlists as $playlist) {
            if ($item) {
                $playlist->$pivotRelation()->detach($item->id);
            }
        }

        return [
            'removed' => true,
            'message' => 'Removed from Watch Later',
        ];
    }

    public function clearWatchLater(Request $request): array
    {
        $playlists = auth()->user()->playlists()
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Watch Later%')
                  ->orWhere('name', 'LIKE', '%watch later%');
            })
            ->get();

        foreach ($playlists as $playlist) {
            $playlist->videos()->detach();
            $playlist->reels()->detach();
        }

        return [
            'status' => 'success',
            'message' => 'Watch Later cleared'
        ];
    }

    public function getMembershipStatus(Request $request, $id): array
    {
        $type = $request->type ?? 'video';
        $user = auth()->user();

        session_write_close();

        if ($type === 'reel') {
            $item = Reel::where('id', $id)->first() ?? Reel::where('slug', $id)->first();
            $pivotTable = 'playlist_reel';
            $foreignKey = 'reel_id';
            $isLiked = $item ? $item->isLikedBy($user) : false;
        } else {
            $item = Video::where('id', $id)->first() ?? Video::where('slug', $id)->first();
            $pivotTable = 'playlist_video';
            $foreignKey = 'video_id';
            $isLiked = $item ? $item->isLikedBy($user) : false;
        }

        if (!$item) {
            return ['status' => 'error', 'message' => 'Content not found.'];
        }

        $playlistIds = $user->playlists()->pluck('id')->toArray();
        $memberships = DB::table($pivotTable)
            ->whereIn('playlist_id', $playlistIds)
            ->where($foreignKey, $item->id)
            ->pluck('playlist_id')
            ->toArray();

        $playlists = $user->playlists()->get()->map(function($pl) use ($memberships) {
            return [
                'id' => $pl->id,
                'name' => $pl->name,
                'is_member' => in_array($pl->id, $memberships)
            ];
        });

        $watchLaterPlaylist = $user->playlists()
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Watch Later%')
                  ->orWhere('name', 'LIKE', '%watch later%');
            })
            ->first();

        $inWatchLater = $watchLaterPlaylist && in_array($watchLaterPlaylist->id, $memberships);

        return compact('playlists', 'inWatchLater', 'isLiked');
    }

    public function destroy(Playlist $playlist): void
    {
        $playlist->delete();
    }
}
