<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ContentService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function telemetry()
    {
        $pageTitle = 'Telemetry Dashboard';
        extract($this->service->getTelemetryData());

        return view('admin.analytics.telemetry', compact('pageTitle', 'chartData', 'todayRollup'));
    }

    public function dashboard(Request $request)
    {
        $pageTitle = 'Platform Analytics';
        extract($this->service->getDashboardAnalytics($request));

        if ($request->ajax() && $request->has('page')) {
            return view('admin.analytics.partials.online_users', compact('onlineUsers'))->render();
        }

        return view('admin.analytics.index', compact(
            'pageTitle', 'latest', 'history', 'dauToday', 'newUsersToday', 'videosToday', 'dauMauRatio', 'onlineUsers', 'totalViews', 'totalUsers', 'totalVideos', 'growth', 'topVideos', 'recentActivity', 'avgWatchTimePerUserToday', 'avgWatchTimePerUserOverall', 'sessionsPerUser', 'returnRate'
        ));
    }

    public function monetization()
    {
        $pageTitle = 'Monetization & Growth';
        extract($this->service->getMonetizationAnalytics());

        return view('admin.analytics.monetization', compact(
            'pageTitle', 'latest', 'history', 'arpu', 'trafficSources',
            'revenueAds', 'revenueSubs', 'revenuePurchases', 'todayRevenue', 'totalRevenue30', 'recentTransactions'
        ));
    }

    public function content()
    {
        $pageTitle = 'Content Performance';
        extract($this->service->getContentAnalytics());

        return view('admin.analytics.content', compact('pageTitle', 'topContent', 'scrollStopRate'));
    }

    public function retention()
    {
        $pageTitle = 'User Retention';
        extract($this->service->getRetentionAnalytics());

        return view('admin.analytics.retention', compact('pageTitle', 'retentionData', 'stats'));
    }

    public function technical()
    {
        $pageTitle = 'Technical Quality';
        extract($this->service->getTechnicalAnalytics());

        return view('admin.analytics.technical', compact(
            'pageTitle', 'apiPerformance', 'errorRates', 'playerHealth', 'playerHealthStatus'
        ));
    }

    public function creators(Request $request)
    {
        $pageTitle = 'Creator Excellence';
        extract($this->service->getCreatorAnalytics($request));

        return view('admin.analytics.creators', compact('pageTitle', 'leaderboard', 'topByEarnings', 'topBySubscribers'));
    }

    public function google()
    {
        $pageTitle = 'Google Analytics Platform';
        return view('admin.analytics.google', compact('pageTitle'));
    }
}
