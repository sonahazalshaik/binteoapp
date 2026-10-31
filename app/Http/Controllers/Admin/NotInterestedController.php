<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\SecurityService;
use Illuminate\Http\Request;

class NotInterestedController extends Controller
{
    public function __construct(private SecurityService $securityService) {}

    public function index()
    {
        $pageTitle = 'Not Interested Videos';
        $interactions = $this->securityService->getNotInterestedVideos();
        return view('admin.videos.not_interested', compact('pageTitle', 'interactions'));
    }

    public function destroy($id)
    {
        $this->securityService->deleteNotInterestedVideo($id);
        $notify[] = ['success', 'Interaction removed successfully'];
        return back()->withNotify($notify);
    }
}
