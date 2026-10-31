<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Services\Frontend\ReelService;
use Illuminate\Http\Request;

class ReelActionController extends Controller
{
    protected ReelService $reelService;

    public function __construct(ReelService $reelService)
    {
        $this->reelService = $reelService;
    }

    public function saveAudio($slug)
    {
        $result = $this->reelService->saveAudio($slug);

        if (isset($result['message'])) {
            return response()->json($result);
        }

        return response()->json($result);
    }

    public function saveAudioDirect($musicId)
    {
        $result = $this->reelService->saveAudioDirect($musicId);

        return response()->json($result);
    }

    public function useAudio($slug)
    {
        $result = $this->reelService->useAudio($slug);

        return redirect($result['redirect']);
    }

    public function duet($slug)
    {
        $result = $this->reelService->duet($slug);

        if (isset($result['error'])) {
            return back()->withNotify([['error', $result['error']]]);
        }

        return redirect($result['redirect']);
    }
}
