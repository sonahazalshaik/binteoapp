<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\Video;
use App\Models\Reel;
use App\Services\Frontend\UploadService;
use Illuminate\Http\Request;

class BunnyUploadController extends Controller
{
    protected UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function prepareUpload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'draft_id' => 'nullable|integer|exists:videos,id',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'visibility' => 'nullable|in:0,1,public,unlisted,private,draft',
            'is_premium' => 'nullable|boolean',
            'price' => 'nullable|numeric|min:0',
            'pricing_tier' => 'nullable|string|in:free,premium,exclusive',
            'is_age_restricted' => 'nullable|boolean',
            'location' => 'nullable|string|max:255',
            'duration' => 'nullable|string',
            'language' => 'nullable|string|max:255',
        ]);

        $result = $this->uploadService->prepareUpload($request);
        return response()->json($result);
    }

    public function autoSaveDraft(Request $request)
    {
        $result = $this->uploadService->autoSaveDraft($request);
        return response()->json($result);
    }
    
    public function activeDrafts(Request $request)
    {
        $draftIds = \App\Models\Video::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->pluck('id');
            
        return response()->json(['active_drafts' => $draftIds]);
    }

    public function uploadThumbnail(Request $request, Video $video)
    {
        $request->validate(['thumbnail' => 'required|image|max:20480']);

        $result = $this->uploadService->uploadThumbnail($request, $video);

        if (isset($result['error'])) {
            return response()->json($result, 403);
        }

        return response()->json($result);
    }

    public function checkStatus($video)
    {
        $videoModel = ($video instanceof Video) ? $video : Video::where('id', $video)->orWhere('slug', $video)->firstOrFail();
        $result = $this->uploadService->checkStatus($videoModel);

        if (isset($result['error'])) {
            if ($result['error'] === 'Unauthorized') {
                return response()->json($result, 403);
            }
            if ($result['error'] === 'Not a Bunny Stream video') {
                return response()->json($result, 400);
            }
            return response()->json($result, 500);
        }

        return response()->json($result);
    }

    public function prepareReelUpload(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'visibility' => 'nullable|in:0,1,public,unlisted,private,draft',
            'location' => 'nullable|string|max:255',
            'duration' => 'nullable|string',
            'language' => 'nullable|string|max:255',
        ]);

        $result = $this->uploadService->prepareReelUpload($request);
        return response()->json($result);
    }

    public function uploadReelThumbnail(Request $request, Reel $reel)
    {
        $request->validate(['thumbnail' => 'required|image|max:20480']);

        $result = $this->uploadService->uploadReelThumbnail($request, $reel);

        if (isset($result['error'])) {
            return response()->json($result, 403);
        }

        return response()->json($result);
    }

    public function checkReelStatus($reel)
    {
        $reelModel = ($reel instanceof Reel) ? $reel : Reel::where('id', $reel)->orWhere('slug', $reel)->firstOrFail();
        $result = $this->uploadService->checkReelStatus($reelModel);

        if (isset($result['error'])) {
            if ($result['error'] === 'Unauthorized') {
                return response()->json($result, 403);
            }
            if ($result['error'] === 'Not a Bunny Stream reel') {
                return response()->json($result, 400);
            }
            return response()->json($result, 500);
        }

        return response()->json($result);
    }

    public function logError(Request $request)
    {
        $request->validate([
            'error_message' => 'required|string',
            'type' => 'required|string|in:video,reel',
            'details' => 'nullable|array',
        ]);

        \Illuminate\Support\Facades\Log::error("TUS Upload Error ({$request->type}): {$request->error_message}", [
            'user_id' => auth()->id(),
            'details' => $request->details,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        if ($request->type === 'reel') {
            \Illuminate\Support\Facades\Log::channel('reels')->error('[REEL-WORKER] fallback/error', [
                'error' => $request->error_message,
                'details' => $request->details,
                'user_id' => auth()->id(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function logDraftEvent(Request $request)
    {
        $request->validate([
            'event' => 'required|string',
            'draft_id' => 'nullable|integer',
            'bunny_id' => 'nullable|string',
            'upload_method' => 'nullable|string',
            'progress' => 'nullable|numeric',
            'uploaded_bytes' => 'nullable|numeric',
            'total_bytes' => 'nullable|numeric',
            'file_name' => 'nullable|string',
            'file_size' => 'nullable|numeric',
            'reason' => 'nullable|string',
            'details' => 'nullable|array',
        ]);

        $data = $request->except(['access_key', 'accessKey', 'apiKey', 'token', 'password']);
        $this->uploadService->logDraftEvent($request->input('event'), $data, 'info');

        return response()->json(['success' => true]);
    }
}
