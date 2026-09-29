<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\Admin\SecurityService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private SecurityService $securityService) {}

    public function index()
    {
        $reports = $this->securityService->getReports();
        return view('admin.reports.index', compact('reports'));
    }

    public function resolve(Report $report)
    {
        $this->securityService->resolveReport($report);

        $notify[] = ['success', 'Report marked as resolved.'];
        return back()->withNotify($notify);
    }

    public function strike(Report $report)
    {
        if (!$report->video_id) {
            $notify[] = ['error', 'This report is not associated with a video.'];
            return back()->withNotify($notify);
        }

        $this->securityService->strikeFromReport($report);

        $notify[] = ['success', 'Report resolved and strike issued successfully.'];
        return back()->withNotify($notify);
    }

    public function destroy(Report $report)
    {
        $this->securityService->deleteReport($report);
        $notify[] = ['success', 'Report deleted successfully.'];
        return back()->withNotify($notify);
    }

    public function destroyContent(Report $report)
    {
        $this->securityService->destroyReportedContent($report);

        $notify[] = ['success', 'Reported content has been purged.'];
        return back()->withNotify($notify);
    }

    public function transaction(Request $request, $userId = null)
    {
        $pageTitle = 'Transaction Logs';

        $result = $this->securityService->getTransactions($userId);
        $transactions = $result['transactions'];
        $remarks = $result['remarks'];

        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function playlistPurchasedHistory(Request $request)
    {
        $pageTitle = 'Video Purchased History';
        $purchasedVideos = $this->securityService->getPurchasedVideos();
        return view('admin.reports.videos', compact('pageTitle', 'purchasedVideos'));
    }

    public function planPurchasedHistory(Request $request)
    {
        $pageTitle = 'Plan Purchased History';
        $purchasedPlans = $this->securityService->getPurchasedPlans();
        return view('admin.reports.plans', compact('pageTitle', 'purchasedPlans'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = $this->securityService->getLoginHistory();
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = $this->securityService->getLoginHistory($ip);
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = $this->securityService->getNotificationHistory();
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = $this->securityService->getEmailDetails($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function watchHistory(Request $request)
    {
        $pageTitle = 'Users Watch History';
        $logs = $this->securityService->getWatchHistory($request->role);

        return view('admin.reports.watch_history', compact('pageTitle', 'logs'));
    }
}
