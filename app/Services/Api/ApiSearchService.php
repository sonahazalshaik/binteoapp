<?php

namespace App\Services\Api;

use App\Models\Video;
use App\Models\Reel;
use App\Models\Channel;
use App\Models\MarketPlace;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiSearchService
{
    public function suggestions(Request $request)
    {
        $query = $request->get('q');

        $type = $request->get('type');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'search_suggestions_' . $query . '_' . ($type ?? 'all');
        $suggestions = Cache::remember($cacheKey, 600, function () use ($query, $type) {
            $suggestions = collect();

            if ($type !== 'marketplace') {

            $channels = Channel::withoutGlobalScope('has_slug')
                ->select('channels.id', 'channels.user_id', 'channels.name', 'channels.avatar')
                ->join('users', 'users.id', '=', 'channels.user_id')
                ->where(function ($q) use ($query) {
                    if (str_starts_with($query, '@')) {
                        $q->where('users.username', ltrim($query, '@'));
                    } else {
                        $q->where('channels.name', 'LIKE', "%{$query}%")
                          ->orWhere('users.username', 'LIKE', "%{$query}%");
                    }
                })
                ->where('users.status', Status::USER_ACTIVE)
                ->take(3)
                ->get()
                ->map(function ($channel) {
                    $channel->load('user:id,username,name');
                    $words = explode(' ', trim($channel->name));
                    $initials = count($words) >= 2
                        ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1))
                        : strtoupper(substr($channel->name, 0, 1) . substr($channel->name, -1));

                    return [
                        'id' => 'c_' . $channel->id,
                        'title' => $channel->name,
                        'thumbnail' => $channel->avatar ? getImage(getFilePath('channelAvatar') . '/' . $channel->avatar) : null,
                        'initials' => $initials,
                        'url' => route('channels.show', $channel->id),
                        'channel' => '@' . ($channel->user->username ?? 'channel'),
                        'type' => 'Channel',
                    ];
                });

            $suggestions = $suggestions->concat($channels);

            $reels = Reel::withoutGlobalScope('has_slug')
                ->select('reels.id', 'reels.user_id', 'reels.title', 'reels.slug', 'reels.thumbnail_path', 'reels.bunny_id')
                ->join('users', 'users.id', '=', 'reels.user_id')
                ->where('users.status', Status::USER_ACTIVE)
                ->whereIn('reels.status', [Status::PUBLISHED, 'published', 'ready'])
                ->whereIn('reels.visibility', [Status::PUBLIC, '0', 'public'])
                ->where(function ($q) use ($query) {
                    $q->where('reels.title', 'LIKE', "%{$query}%")
                      ->orWhere('users.username', 'LIKE', "%{$query}%")
                      ->orWhere('users.name', 'LIKE', "%{$query}%");
                })
                ->with('user.channel:id,user_id,name')
                ->latest('reels.created_at')
                ->take(3)
                ->get()
                ->map(function ($reel) {
                    return [
                        'id' => 'r_' . $reel->id,
                        'title' => $reel->title,
                        'thumbnail' => $reel->getThumbnailUrl(),
                        'url' => route('reels.index') . '?reel=' . $reel->slug,
                        'channel' => $reel->user?->channel?->name ?? $reel->user?->fullname,
                        'type' => 'Reel',
                    ];
                });

            $suggestions = $suggestions->concat($reels);

            $videos = Video::withoutGlobalScope('has_slug')
                ->select('videos.id', 'videos.user_id', 'videos.title', 'videos.slug', 'videos.thumbnail_path', 'videos.bunny_id', 'videos.video_path')
                ->join('users', 'users.id', '=', 'videos.user_id')
                ->where('users.status', Status::USER_ACTIVE)
                ->whereIn('videos.status', [Status::PUBLISHED, 'published', 'ready'])
                ->whereIn('videos.visibility', [Status::PUBLIC, '0', 'public'])
                ->where('videos.title', 'LIKE', "%{$query}%")
                ->whereNotExists(function ($sq) {
                    $sq->select(DB::raw(1))
                       ->from('copyright_strikes')
                       ->whereColumn('copyright_strikes.video_id', 'videos.id')
                       ->where('copyright_strikes.status', 'active');
                })
                ->with(['user.channel:id,user_id,name'])
                ->latest('videos.created_at')
                ->take(5)
                ->get()
                ->map(function ($video) {
                    return [
                        'id' => 'v_' . $video->id,
                        'title' => $video->title,
                        'thumbnail' => $video->getThumbnailUrl(),
                        'url' => route('videos.show', $video),
                        'channel' => $video->user?->channel?->name ?? $video->user?->fullname,
                        'type' => 'Video',
                    ];
                });

            $suggestions = $suggestions->concat($videos);
            }

            $talents = MarketPlace::active()
                ->select('id', 'name', 'business_name', 'image')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('business_name', 'LIKE', "%{$query}%");
                })
                ->latest()
                ->take($type === 'marketplace' ? 10 : 2)
                ->get()
                ->map(function ($talent) {
                    $words = explode(' ', trim($talent->name));
                    $initials = count($words) >= 2
                        ? strtoupper(substr($words[0], 0, 1) . substr($words[count($words) - 1], 0, 1))
                        : strtoupper(substr($talent->name, 0, 1) . substr($talent->name, -1));

                    return [
                        'id' => 't_' . $talent->id,
                        'title' => $talent->name,
                        'thumbnail' => $talent->photoUrl(),
                        'initials' => $initials,
                        'url' => route('marketplace.portfolio', $talent->id),
                        'channel' => $talent->business_name ?? 'Professional Talent',
                        'type' => 'Talent',
                    ];
                });

            $suggestions = $suggestions->concat($talents);

            return $suggestions->toArray();
        });

        return response()->json($suggestions);
    }

    public function mentions(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([]);
        }

        $channels = Channel::withoutGlobalScope('has_slug')
            ->select('channels.id', 'channels.name', 'channels.avatar', 'users.username')
            ->join('users', 'users.id', '=', 'channels.user_id')
            ->where('users.status', Status::USER_ACTIVE)
            ->where(function ($q) use ($query) {
                $q->where('channels.name', 'LIKE', "%{$query}%")
                  ->orWhere('users.username', 'LIKE', "%{$query}%");
            })
            ->take(5)
            ->get()
            ->map(function ($channel) {
                return [
                    'name' => $channel->name,
                    'username' => $channel->username,
                    'avatar' => getImage(getFilePath('channelAvatar') . '/' . $channel->avatar),
                ];
            });

        return response()->json($channels);
    }
}
