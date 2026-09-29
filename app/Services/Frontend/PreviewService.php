<?php

namespace App\Services\Frontend;

use App\Models\Channel;
use App\Models\Playlist;
use App\Models\Video;
use App\Models\Reel;
use App\Models\Membership;

class PreviewService
{
    public function channel($slug = null): array
    {
        $channel = $slug
            ? Channel::withoutGlobalScope('has_slug')->with('user')->whereSlug($slug)->firstOrFail()
            : auth()->user()->channel;

        return compact('channel');
    }

    public function playlist($slug = null): array
    {
        $playlist = Playlist::whereSlug($slug)->with('videos.user.channel')->firstOrFail();
        return compact('playlist');
    }

    public function playlistVideos($playlistSlug = null, $userSlug = null): array
    {
        $playlist = Playlist::whereSlug($playlistSlug)->with('videos.user.channel')->firstOrFail();
        return compact('playlist');
    }

    public function shorts($slug = null): array
    {
        $reel = Reel::forUser()->whereSlug($slug)->firstOrFail();
        return compact('reel');
    }

    public function about($slug = null): array
    {
        $channel = Channel::withoutGlobalScope('has_slug')->with('user')->whereSlug($slug)->firstOrFail();
        return compact('channel');
    }

    public function monthlyPlan($slug = null): array
    {
        $membership = Membership::whereSlug($slug)->firstOrFail();
        return compact('membership');
    }
}


