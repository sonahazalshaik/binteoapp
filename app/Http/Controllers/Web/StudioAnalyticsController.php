<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Services\Frontend\StudioService;
use Illuminate\Http\Request;

class StudioAnalyticsController extends Controller
{
    protected StudioService $studioService;

    public function __construct(StudioService $studioService)
    {
        $this->studioService = $studioService;
    }

    public function overview(Request $request)
    {
        $data = $this->studioService->overview($request);

        return response()->json($data);
    }

    public function reach(Request $request)
    {
        $data = $this->studioService->reach($request);

        $averageCtr = $data['totalImpressions'] > 0
            ? round(($data['totalViews'] / $data['totalImpressions']) * 100, 1)
            : 0;

        return response()->json($data + compact('averageCtr'));
    }

    public function realtime(Request $request)
    {
        $data = $this->studioService->realtime($request);

        return response()->json($data);
    }

    public function audience(Request $request)
    {
        $data = $this->studioService->audience($request);

        return response()->json($data);
    }
}
