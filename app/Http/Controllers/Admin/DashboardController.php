<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(AdminDashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $pageTitle     = 'Dashboard';
        $widget        = $this->service->getDashboardWidgets();
        $chart         = $this->service->getDashboardCharts();
        $deposit       = $this->service->getDepositStats();
        $withdrawals   = $this->service->getWithdrawalStats();
        $recentUploads = $this->service->getRecentUploads();

        return view('admin.dashboard', compact(
            'pageTitle', 'widget', 'chart', 'deposit', 'withdrawals', 'recentUploads'
        ));
    }

    public function depositAndWithdrawReport(Request $request)
    {
        $report = $this->service->getDepositWithdrawReport($request);
        return response()->json($report);
    }

    public function transactionReport(Request $request)
    {
        $report = $this->service->getTransactionReport($request);
        return response()->json($report);
    }

    public function updateFirebaseToken(Request $request)
    {
        Log::info('[FCM-ADMIN] Token update request received', [
            'admin_id' => auth()->guard('admin')->id(),
            'token_prefix' => substr($request->token ?? '', 0, 20) . '...',
            'device_type' => $request->device_type,
            'ip' => $request->ip()
        ]);

        $request->validate(['token' => 'required|string']);

        $this->service->updateFirebaseToken($request);

        Log::info('[FCM-ADMIN] Token saved successfully');
        return response()->json(['success' => true]);
    }

    public function checkSpace()
    {
        return response()->json($this->service->checkStorageSpace());
    }
}
