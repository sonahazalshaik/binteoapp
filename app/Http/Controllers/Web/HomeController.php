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
        $data = $this->homeService->getHomepageData($request);

        return view('frontend.index', $data);
    }

    public function notifications(Request $request)
    {
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json($this->homeService->getNotifications());
        }

        if (auth()->check()) {
            $this->homeService->markNotificationsRead();
        }

        $pageTitle = 'My Notifications';
        $notifications = auth()->user()->userNotifications()->latest()->paginate(getPaginate());
        return view('frontend.client.notifications', compact('pageTitle', 'notifications'));
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
        return response()->json($this->homeService->fetchVideos($request));
    }
}
