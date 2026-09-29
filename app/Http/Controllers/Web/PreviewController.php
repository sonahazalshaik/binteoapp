<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Services\Frontend\PreviewService;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    protected PreviewService $previewService;

    public function __construct(PreviewService $previewService)
    {
        $this->previewService = $previewService;
    }

    private function resolveChannel($slug)
    {
        // Fallback to fetch user by slug, since the user is the channel in Phase 3
        return User::where('slug', $slug)->firstOrFail();
    }

    public function channel($slug = null)
    {
        $user = $this->resolveChannel($slug);
        // We reuse the existing native channel show view logic mapped to the user!
        $channel = $user->channel()->first() ?? (object) ['name' => $user->channel_name, 'user' => $user, 'subscribers_count' => 0];
        
        return view('frontend.channels.show', compact('channel'));
    }

    public function playlist($slug = null)
    {
        $user = $this->resolveChannel($slug);
        $notify[] = ['info', 'Playlists are shown on your channel directly.'];
        return redirect()->route('preview.channel', $slug)->withNotify($notify);
    }

    public function playlistVideos($playlistSlug = null, $userSlug = null)
    {
        return redirect()->route('preview.channel', $userSlug);
    }

    public function shorts($slug = null)
    {
        return redirect()->route('preview.channel', $slug);
    }

    public function about($slug = null)
    {
        return redirect()->route('preview.channel', $slug);
    }

    public function monthlyPlan($slug = null)
    {
        $user = $this->resolveChannel($slug);
        $notify[] = ['info', 'Monthly plans preview coming soon.'];
        return redirect()->route('preview.channel', $slug)->withNotify($notify);
    }
}


