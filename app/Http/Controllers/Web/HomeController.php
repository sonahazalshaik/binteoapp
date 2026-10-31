<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Services\Frontend\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected HomeService $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index(Request $request)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        $data = $this->homeService->getHomepageData($request);

        return view('frontend.index', $data);
    }

    public function notifications(Request $request)
    {
        // NOTE: this page route ALWAYS returns Blade HTML. The bell JSON
        // lives on notificationsGrid() below, so no navigation, reload, or
        // browser Back can ever receive a raw JSON document from this URL.
        if (auth()->check()) {
            $this->homeService->markNotificationsRead();
        }

        $pageTitle = 'My Notifications';
        $notifications = auth()->user()->userNotifications()->latest()->paginate(getPaginate());
        return view('frontend.client.notifications', compact('pageTitle', 'notifications'));
    }

    /**
     * JSON-only endpoint for the bell polling fetch().
     * Never linked or navigated to; non-AJAX access gets 404.
     */
    public function notificationsGrid(Request $request)
    {
        if ($request->header('Sec-Fetch-Mode') === 'navigate' || $request->header('Sec-Fetch-Dest') === 'document') {
            abort(404);
        }

        if (!($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json')) {
            abort(404);
        }

        return response()->json($this->homeService->getNotifications());
    }

    public function readAllNotifications()
    {
        $this->homeService->markNotificationsRead();
        return response()->json(['status' => 'success']);
    }

    public function category($slug)
    {
        $data = $this->homeService->getCategoryVideos($slug);

        return view('frontend.category_videos', $data);
    }

    public function fetchVideos(Request $request)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        return response()->json($this->homeService->fetchVideos($request));
    }
}
