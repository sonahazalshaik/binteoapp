<?php

namespace App\Services\Admin;

use App\Constants\Status;
use App\Models\Playlist;
use App\Models\Reel;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PlaylistService
{
    public function getAllPlaylists()
    {
        return Playlist::searchable(['name', 'user:username'])->with(['user', 'videos', 'reels'])->latest()->paginate(getPaginate());
    }

    public function findPlaylist($id): Playlist
    {
        return Playlist::findOrFail($id);
    }

    public function createPlaylist(Request $request): Playlist
    {
        $playlist = new Playlist();
        $this->fillPlaylistData($playlist, $request);
        $playlist->save();

        if ($request->video_ids) {
            $playlist->videos()->sync($request->video_ids);
        }
        if ($request->reel_ids) {
            $playlist->reels()->sync($request->reel_ids);
        }

        return $playlist;
    }

    public function updatePlaylist(Request $request, $id): Playlist
    {
        $playlist = Playlist::findOrFail($id);
        $this->fillPlaylistData($playlist, $request);
        $playlist->save();

        $playlist->videos()->sync($request->video_ids ?? []);
        $playlist->reels()->sync($request->reel_ids ?? []);

        return $playlist;
    }

    public function deletePlaylist($id): void
    {
        Playlist::findOrFail($id)->delete();
    }

    public function getUserContent($userId): array
    {
        $videos = Video::where('user_id', $userId)->where('status', Status::PUBLISHED)->get()->map(function($v) {
            $v->thumbnail = $v->getThumbnailUrl();
            return $v;
        });

        $reels = Reel::where('user_id', $userId)->where('status', Status::PUBLISHED)->get()->map(function($r) {
            $r->thumbnail = $r->getThumbnailUrl();
            return $r;
        });

        return compact('videos', 'reels');
    }

    public function getPlaylistVideos($id, $perPage = 15)
    {
        $playlist = Playlist::findOrFail($id);
        return $playlist->videos()->paginate($perPage);
    }

    protected function fillPlaylistData(Playlist $playlist, Request $request): void
    {
        $playlist->user_id = $request->user_id ?? $playlist->user_id;

        if (Schema::hasColumn('playlists', 'title')) {
            $playlist->title = $request->title;
        } elseif (Schema::hasColumn('playlists', 'name')) {
            $playlist->name = $request->title;
        }

        if (Schema::hasColumn('playlists', 'description')) {
            $playlist->description = $request->description;
        }
        if (Schema::hasColumn('playlists', 'visibility')) {
            $playlist->visibility = $request->visibility;
        }
        if (Schema::hasColumn('playlists', 'price')) {
            $playlist->price = $request->price ?? 0;
        }
        if (Schema::hasColumn('playlists', 'playlist_subscription')) {
            $playlist->playlist_subscription = $request->playlist_subscription ? Status::ENABLE : Status::DISABLE;
        }
    }
}
