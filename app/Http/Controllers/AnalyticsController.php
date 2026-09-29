<?php

namespace App\Http\Controllers;

use App\Services\Frontend\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function pingVideo(Request $request)
    {
        $result = $this->analyticsService->pingVideo($request);

        $statusCode = (isset($result['status']) && $result['status'] === 'error') ? 400 : 200;

        return response()->json($result, $statusCode);
    }

    public function pingSession(Request $request)
    {
        $result = $this->analyticsService->pingSession($request);

        return response()->json($result);
    }
}
