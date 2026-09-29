<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ContentService;

class GrowthController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        extract($this->service->getGrowthDashboard());

        return view('admin.growth.index', compact(
            'registrations', 'visitors', 'totalUsers', 'totalVisitors',
            'newUsers', 'newVisitors', 'conversionRate', 'liveUsersCount'
        ));
    }

    public function revenueReports()
    {
        extract($this->service->getRevenueReports());

        return view('admin.analytics.revenue', compact('monthlyRevenue', 'weeklyRevenue'));
    }

    public function videoPerformance()
    {
        extract($this->service->getVideoAnalytics());

        return view('admin.analytics.videos', compact('topVideos', 'underperforming'));
    }

    public function audienceAnalytics()
    {
        extract($this->service->getAudienceAnalytics());

        return view('admin.analytics.audience', compact('devices', 'countries'));
    }
}
