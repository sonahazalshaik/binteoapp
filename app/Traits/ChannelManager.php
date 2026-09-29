<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\GatewayCurrency;
use App\Models\Plan;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;

trait ChannelManager {
    protected $guest = false;

    public function channel($slug = null) {
        $user = auth()->user();
        if ($slug) {
            $user = User::where('profile_complete', Status::YES)
                ->active()->where('slug', $slug)
                ->firstOrFail();
        }
        $pageTitle = 'Channel Profile';

        $videos = Video::where('user_id', $user->id)
            ->latest()
            ->paginate(getPaginate());

        $subscriberCount = $user->subscribers()->count();
        $videosCount     = $user->videos()->count();
        $bladeName       = 'profile';

        return view('frontend.channels.show', compact('user', 'pageTitle', 'videos', 'bladeName', 'subscriberCount', 'videosCount'));
    }

    public function shorts($slug = null) {
        if ($slug) {
            $user = User::where('profile_complete', Status::YES)
                ->active()->where('slug', $slug)
                ->firstOrFail();
        }

        $pageTitle = 'All Shorts';
        $videos    = Video::where('user_id', $user->id)
            ->latest()
            ->paginate(getPaginate());

        $subscriberCount = $user->subscribers()->count();
        $videosCount     = $user->videos()->count();
        $bladeName       = 'shorts';

        return view('frontend.channels.show', compact('user', 'subscriberCount', 'videosCount', 'pageTitle', 'videos', 'bladeName'));
    }

    public function playlist($slug = null) {
        if ($slug) {
            $user = User::where('profile_complete', Status::YES)
                ->active()->where('slug', $slug)
                ->firstOrFail();
        }

        $pageTitle = 'Playlist';
        $playlists = Playlist::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        $subscriberCount = $user->subscribers()->count();
        $videosCount     = $user->videos()->count();
        $bladeName       = 'playlist';

        return view('frontend.channels.show', compact('pageTitle', 'videosCount', 'subscriberCount', 'playlists', 'bladeName', 'user'));
    }

    public function about($slug = null) {
        if ($slug) {
            $user = User::where('profile_complete', Status::YES)->active()->where('slug', $slug)->firstOrFail();
        }

        $subscriberCount = $user->subscribers()->count();
        $videosCount     = $user->videos()->count();

        $bladeName = 'about';
        $pageTitle = 'About Channel';
        return view('frontend.channels.show', compact('pageTitle', 'videosCount', 'subscriberCount', 'bladeName', 'user'));
    }

    public function monthlyPlan($slug) {
        abort_if(!gs('is_monthly_subscription'), 404);

        $user = User::where('profile_complete', Status::YES)->active()->where('slug', $slug)->firstOrFail();

        $plans = Plan::active()
            ->where('user_id', $user->id)
            ->with('videos', 'playlists')
            ->orderBy('price')
            ->get();

        $subscriberCount = $user->subscribers()->count();
        $videosCount     = $user->videos()->count();

        $bladeName = 'monthly_plan';
        $pageTitle = 'Monthly Plans';
        return view('frontend.channels.show', compact('pageTitle', 'videosCount', 'subscriberCount', 'plans', 'bladeName', 'user'));
    }

    public function playlistVideos($playlistSlug, $slug = null) {
        if ($slug) {
            $user = User::where('profile_complete', Status::YES)
                ->active()->where('slug', $slug)
                ->firstOrFail();
        }

        $playlist = Playlist::where('user_id', $user->id)->where('slug', $playlistSlug)
            ->firstOrFail();

        $videos = $playlist
            ->videos()
            ->paginate(getPaginate());

        $subscriberCount = $user->subscribers()->count();
        $videosCount     = $user->videos()->count();

        $bladeName = 'videos';
        $pageTitle = 'Playlists Videos';

        return view('frontend.channels.show', compact('pageTitle', 'videosCount', 'subscriberCount', 'bladeName', 'playlist', 'user', 'videos'));
    }
}
