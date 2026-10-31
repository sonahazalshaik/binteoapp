<?php

namespace App\Http\Controllers;

use App\Services\Frontend\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsEventController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function logVideoEvent(Request $request)
    {
        $result = $this->analyticsService->logVideoEvent($request);

        return response()->json($result);
    }

    public function updateWatchProgress(Request $request)
    {
        $result = $this->analyticsService->updateWatchProgress($request);

        return response()->json($result);
    }
}
