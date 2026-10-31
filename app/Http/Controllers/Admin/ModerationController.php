<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\CommentService;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    protected $service;

    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    public function reportedVideos()
    {
        $reports = $this->service->getReportedVideos(20);
        return view('admin.moderation.reports.videos', compact('reports'));
    }

    public function reportedUsers()
    {
        $reports = $this->service->getReportedUsers(20);
        return view('admin.moderation.reports.users', compact('reports'));
    }

    public function appeals()
    {
        $appeals = $this->service->getAppeals(20);
        return view('admin.moderation.appeals', compact('appeals'));
    }

    public function handleReport(Request $request, $id)
    {
        $this->service->handleReport($id, $request->status, $request->feedback);

        $notify[] = ['success', 'Report processed successfully.'];
        return back()->withNotify($notify);
    }
    public function struckUsers()
    {
        $strikes = $this->service->getStruckUsers(20);
        $pageTitle = 'Manage Copyright Strikes';
        return view('admin.moderation.strikes', compact('strikes', 'pageTitle'));
    }

    public function removeStrike($id)
    {
        $this->service->removeStrike($id);

        $notify[] = ['success', 'Strike removed successfully.'];
        return back()->withNotify($notify);
    }

    public function reportedReels()
    {
        $reports = $this->service->getReportedReels(20);
        $pageTitle = 'Reported Reels';
        return view('admin.moderation.reports.reels', compact('reports', 'pageTitle'));
    }

    public function handleReelReport(Request $request, $id)
    {
        $this->service->handleReelReport($id, $request->status, $request->feedback);

        $notify[] = ['success', 'Reel report processed successfully.'];
        return back()->withNotify($notify);
    }
}
