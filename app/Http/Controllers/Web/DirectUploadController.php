<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Services\Frontend\UploadService;
use Illuminate\Http\Request;

class DirectUploadController extends Controller
{
    protected UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file',
            'video_id' => 'required|integer',
        ]);

        $result = $this->uploadService->uploadVideoDirect($request);

        if (!$result['success']) {
            $statusCode = $result['message'] === 'Video not found' ? 404 : 500;
            return response()->json($result, $statusCode);
        }

        return response()->json($result);
    }

    public function uploadReel(Request $request)
    {
        $request->validate([
            'video' => 'required|file',
            'reel_id' => 'required|integer',
        ]);

        $result = $this->uploadService->uploadReelDirect($request);

        if (!$result['success']) {
            $statusCode = $result['message'] === 'Reel not found' ? 404 : 500;
            return response()->json($result, $statusCode);
        }

        return response()->json($result);
    }
}
