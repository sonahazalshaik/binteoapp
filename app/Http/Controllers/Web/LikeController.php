<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Services\Frontend\VideoService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected VideoService $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function toggle($id)
    {
        $result = $this->videoService->toggleLike($id);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }

    /**
     * Idempotent remove from Liked videos (DELETE-only, never re-likes).
     */
    public function remove($id)
    {
        $result = $this->videoService->removeLike($id);

        if (isset($result['status']) && $result['status'] === 'error') {
            return response()->json($result, 404);
        }

        return response()->json($result);
    }
}


