<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\LiveStream;
use App\Services\Frontend\LiveStreamService;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    protected LiveStreamService $liveStreamService;

    public function __construct(LiveStreamService $liveStreamService)
    {
        $this->liveStreamService = $liveStreamService;
    }

    public function show($slug)
    {
        $data = $this->liveStreamService->show($slug);

        return view('frontend.live.show', $data);
    }

    public function sendMessage(Request $request, LiveStream $stream)
    {
        $result = $this->liveStreamService->sendMessage($request, $stream);

        return response()->json($result);
    }

    public function goLive(Request $request)
    {
        $stream = $this->liveStreamService->goLive($request);

        return redirect()->route('live.show', $stream->slug);
    }
}
