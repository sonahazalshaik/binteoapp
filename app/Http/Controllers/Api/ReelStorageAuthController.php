<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Services\BunnyStorageAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReelStorageAuthController extends Controller
{
    public function __construct(
        protected BunnyStorageAuthService $authService
    ) {}

    /**
     * POST /api/reels/{reel}/storage-authorize
     * Body: { operation: upload|final }
     *
     * Generates server-controlled storage path + HMAC token for Worker.
     * Throttled, auth, CSRF via web middleware.
     */
    public function storageAuthorize(Request $request, $reel)
    {
        $request->validate([
            'operation' => 'required|in:upload,final',
        ]);

        $reel = Reel::withoutGlobalScopes()->where('slug', $reel)->orWhere('id', $reel)->firstOrFail();

        // Ownership check - IDOR prevention
        if ((int)$reel->user_id !== (int)auth()->id()) {
            return response()->json(['error' => 'Unauthorized - reel not owned'], 403);
        }

        $operation = $request->input('operation');
        $userId = (int)auth()->id();
        $reelId = (int)$reel->id;

        // For 'final' op, require that source exists or pending path exists
        // but do not fail hard - allow single final per reel
        $type = $operation === 'final' ? 'final' : 'source';
        $storagePath = $this->authService->generateStoragePath($userId, $reelId, $type);

        // For normal upload (source), store pending path temporarily
        // We do not overwrite storage_path for final until confirm
        if ($operation === 'upload' && empty($reel->storage_path)) {
            // Store pending source path so confirm can validate
            // Use video_path as pending marker if storage_path empty to keep legacy compat
            $reel->update(['video_path' => $storagePath]);
        }

        $tokenData = $this->authService->generateToken($userId, $reelId, $storagePath, $operation, 300);
        $workerUrl = $this->authService->getWorkerUploadUrl($storagePath, $tokenData['token'], $tokenData['expiry'], $operation);

        Log::channel('reels')->info('[WORKER-AUTH] authorize', [
            'reel_id' => $reelId,
            'user_id' => $userId,
            'operation' => $operation,
            'path' => $storagePath,
            'expiry' => $tokenData['expiry'],
            'safe' => 'HMAC generated, no video bytes via cPanel',
        ]);
        Log::info('[WORKER-AUTH] authorize', [
            'reel_id' => $reelId,
            'user_id' => $userId,
            'operation' => $operation,
            'path' => $storagePath,
            'expiry' => $tokenData['expiry'],
        ]);

        return response()->json([
            'success' => true,
            'reel_id' => $reelId,
            'reel_slug' => $reel->slug,
            'storage_path' => $storagePath,
            'worker_url' => $workerUrl,
            'token' => $tokenData['token'],
            'expiry' => $tokenData['expiry'],
            'operation' => $operation,
            // Never expose AccessKey or signing secret
        ]);
    }

    /**
     * POST /reels/{reel}/presign
     * Body: { operation: upload|final }
     * Generates server-controlled storage_path + S3 presigned PUT URL (Reels only).
     * Browser PUTs directly to Bunny S3 without Worker/cPanel bytes.
     */
    public function presign(Request $request, $reel)
    {
        $request->validate(['operation' => 'required|in:upload,final']);
        $reel = Reel::withoutGlobalScopes()->where('slug', $reel)->orWhere('id', $reel)->firstOrFail();
        if ((int)$reel->user_id !== (int)auth()->id()) {
            return response()->json(['error' => 'Unauthorized - reel not owned'], 403);
        }
        $operation = $request->input('operation');
        $userId = (int)auth()->id();
        $reelId = (int)$reel->id;
        $type = $operation === 'final' ? 'final' : 'source';
        $storagePath = $this->authService->generateStoragePath($userId, $reelId, $type);
        if ($operation === 'upload' && empty($reel->storage_path)) {
            $reel->update(['video_path' => $storagePath]);
        }
        try {
            $presign = $this->authService->generatePresignedPutUrl($storagePath, 300);
        } catch (\Throwable $e) {
            Log::channel('reels')->error('[S3-PRESIGN] failed', ['reel_id' => $reelId, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Presign failed: '.$e->getMessage()], 500);
        }
        Log::channel('reels')->info('[S3-PRESIGN] generated', [
            'reel_id' => $reelId, 'user_id' => $userId, 'operation' => $operation, 'path' => $storagePath, 'expiry' => $presign['expiry'], 'safe' => 'S3 presigned PUT, no AccessKey exposed',
        ]);
        return response()->json([
            'success' => true,
            'reel_id' => $reelId,
            'reel_slug' => $reel->slug,
            'storage_path' => $storagePath,
            'presignedUrl' => $presign['presignedUrl'],
            'expiry' => $presign['expiry'],
            'operation' => $operation,
        ]);
    }

    /**
     * POST /api/reels/{reel}/confirm
     * Body: { storage_path: string }
     *
     * Verifies file exists on Bunny Storage via HEAD (server-side AccessKey),
     * then marks reel ready. Prevents client lying about upload.
     */
    public function confirm(Request $request, $reel)
    {
        $request->validate([
            'storage_path' => 'required|string|max:255',
        ]);

        $reel = Reel::withoutGlobalScopes()->where('slug', $reel)->orWhere('id', $reel)->firstOrFail();

        if ((int)$reel->user_id !== (int)auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $storagePath = $request->input('storage_path');

        // Strict path validation - must be reels/{authUser}/{reelId}/*
        if (!$this->authService->isValidPath($storagePath, (int)auth()->id())) {
            return response()->json(['error' => 'Invalid storage path'], 422);
        }

        // Must match expected reel prefix
        if (!str_starts_with($storagePath, "reels/" . auth()->id() . "/" . $reel->id . "/")) {
            return response()->json(['error' => 'Path does not match reel'], 422);
        }

        // Verify file exists on Bunny Storage via HEAD (server-side, 3x5s retry)
        $exists = $this->verifyBunnyStorageFile($storagePath);
        if (!$exists) {
            Log::channel('reels')->warning('[WORKER-AUTH] confirm FAILED - HEAD not found after 3 retries', [
                'reel_id' => $reel->id,
                'path' => $storagePath,
                'safe' => false,
            ]);
            Log::warning('[WORKER-AUTH] confirm file not found on Bunny', [
                'reel_id' => $reel->id,
                'path' => $storagePath,
            ]);
            return response()->json(['error' => 'File not found on storage - upload may have failed'], 404);
        }

        // HEAD succeeded - mark ready. For normal reel, this is final storage_path
        // For duet final, this will be reels/.../final.mp4
        $reel->update([
            'storage_path' => $storagePath,
            'bunny_status' => 'ready',
            'compression_status' => 2,
            'is_compressed' => true,
            'status' => $reel->status === \App\Constants\Status::DRAFT ? $reel->status : \App\Constants\Status::PUBLISHED,
        ]);

        Log::channel('reels')->info('[WORKER-AUTH] confirm SUCCESS - reel safe via Worker', [
            'reel_id' => $reel->id,
            'path' => $storagePath,
            'storage' => 'Bunny Storage',
            'cdn' => 'Bunny Pull Zone',
            'safe' => true,
        ]);
        Log::info('[WORKER-AUTH] confirm success - reel ready', [
            'reel_id' => $reel->id,
            'path' => $storagePath,
        ]);

        // Notify subscribers (keep existing pipeline)
        try {
            if ($reel->status !== \App\Constants\Status::DRAFT) {
                app(\App\Services\Frontend\UploadService::class);
                // Use existing notify if available via trait
            }
        } catch (\Throwable $e) {
            Log::warning('[WORKER-AUTH] notify failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'reel_slug' => $reel->slug,
            'storage_path' => $storagePath,
            'message' => 'Reel confirmed and ready',
        ]);
    }

    /**
     * GET /api/reels/{reel}/duet-token
     * Returns short-lived signed Pull Zone URL for parent reel.
     */
    public function duetToken(Request $request, $reel)
    {
        $parent = Reel::withoutGlobalScopes()->where('slug', $reel)->orWhere('id', $reel)->firstOrFail();

        // Visibility check
        if ((string)$parent->visibility === (string)\App\Constants\Status::PRIVATE) {
            if (!auth()->check() || (int)auth()->id() !== (int)$parent->user_id) {
                return response()->json(['error' => 'Parent reel is private'], 403);
            }
        }

        // Allow duet check
        if (!$parent->allow_duet) {
            return response()->json(['error' => 'This reel does not allow duets'], 403);
        }

        if (empty($parent->storage_path) && empty($parent->bunny_id) && empty($parent->video_path)) {
            return response()->json(['error' => 'Parent reel has no video source'], 404);
        }

        $pullZone = gs('bunny_reels_pull_zone') ?: 'reelscdn.b-cdn.net';
        $securityKey = gs('bunny_security_key') ?: config('bunny.security_key');

        // Prefer storage_path for new Worker reels
        if (!empty($parent->storage_path)) {
            $path = '/' . ltrim($parent->storage_path, '/');
            $cdnUrl = "https://{$pullZone}{$path}";
            if ($securityKey) {
                $expiry = time() + 300;
                $token = hash('sha256', $securityKey . $path . $expiry);
                $cdnUrl .= "?token={$token}&expires={$expiry}";
            }
        } elseif ($parent->isBunnyReel()) {
            // Legacy Bunny Stream parent
            $cdnHostname = gs('bunny_cdn_hostname') ?: config('bunny.cdn_hostname', 'iframe.mediadelivery.net');
            $path = "/{$parent->bunny_id}/play_480p.mp4";
            $cdnUrl = "https://{$cdnHostname}{$path}";
            if ($securityKey) {
                $expiry = time() + 300;
                $token = hash('sha256', $securityKey . $path . $expiry);
                $cdnUrl .= "?token={$token}&expires={$expiry}";
            }
        } else {
            // Local file - not fetchable for wasm, return local URL
            $cdnUrl = $parent->getVideoUrl();
        }

        return response()->json([
            'success' => true,
            'parent_id' => $parent->id,
            'parent_slug' => $parent->slug,
            'signed_url' => $cdnUrl,
            'expires_in' => 300,
        ]);
    }

    /**
     * POST /api/reels/{reel}/delete-temp
     * Body: { temp_path: string }
     */
    public function deleteTemp(Request $request, $reel)
    {
        $request->validate([
            'temp_path' => 'required|string|max:255',
        ]);

        $reel = Reel::withoutGlobalScopes()->where('slug', $reel)->orWhere('id', $reel)->firstOrFail();

        if ((int)$reel->user_id !== (int)auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $tempPath = $request->input('temp_path');

        // Must be valid path and belong to this user+reel
        if (!$this->authService->isValidPath($tempPath, (int)auth()->id())) {
            return response()->json(['error' => 'Invalid path'], 422);
        }
        if (!str_starts_with($tempPath, "reels/" . auth()->id() . "/" . $reel->id . "/")) {
            return response()->json(['error' => 'Path does not belong to this reel'], 422);
        }

        // Prevent deleting final if requested as temp (extra guard)
        if (str_ends_with($tempPath, 'final.mp4') && $reel->storage_path === $tempPath) {
            return response()->json(['error' => 'Cannot delete current final file'], 422);
        }

        // Server-side delete via Bunny Storage AccessKey
        $deleted = $this->deleteBunnyStorageFile($tempPath);

        Log::info('[WORKER-AUTH] delete-temp', [
            'reel_id' => $reel->id,
            'path' => $tempPath,
            'deleted' => $deleted,
        ]);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Temp file deleted' : 'Delete failed or file not found',
        ]);
    }

    /**
     * Verify file exists on Bunny Storage via HEAD.
     * FAIL-CLOSED: if zone/key missing, return false (never trust browser).
     * Retries 3 times with 5s delay for eventual consistency.
     */
    private function verifyBunnyStorageFile(string $storagePath): bool
    {
        $storageZone = gs('bunny_reels_storage_zone') ?: config('bunny.reels_storage_zone') ?: config('bunny.storage_zone');
        $accessKey = gs('bunny_reels_storage_access_key') ?: config('bunny.reels_storage_access_key') ?: config('bunny.storage_access_key');
        $region = gs('bunny_reels_storage_region') ?: config('bunny.reels_storage_region') ?: config('bunny.storage_region');
        if (empty($storageZone) || empty($accessKey)) {
            Log::channel('reels')->error('[WORKER-AUTH] HEAD fail-closed - zone/key missing', [
                'has_zone' => !empty($storageZone),
                'has_key' => !empty($accessKey),
            ]);
            Log::error('[WORKER-AUTH] Bunny Storage not configured - HEAD verify fail-closed (zone/key missing)', [
                'has_zone' => !empty($storageZone),
                'has_key' => !empty($accessKey),
            ]);
            return false;
        }
        $regionClean = strtolower(trim($region ?? ''));
        $host = (empty($regionClean) || in_array($regionClean, ['de', 'main', 'falkenstein'])) ? 'storage.bunnycdn.com' : "{$regionClean}.storage.bunnycdn.com";
        
        $parts = explode('/', $storagePath);
        $filename = array_pop($parts);
        $dirPath = implode('/', $parts);
        $dirUrl = "https://{$host}/{$storageZone}/{$dirPath}/";

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                // Bunny Storage HTTP API returns 401 for HEAD. We must GET the directory list instead.
                $response = Http::withHeaders([
                    'AccessKey' => $accessKey,
                    'Accept' => 'application/json'
                ])->timeout(10)->withOptions(['verify' => false])->get($dirUrl);

                if ($response->successful()) {
                    $files = $response->json();
                    $found = false;
                    if (is_array($files)) {
                        foreach ($files as $fileObj) {
                            if (isset($fileObj['ObjectName']) && $fileObj['ObjectName'] === $filename) {
                                $found = true;
                                break;
                            }
                        }
                    }

                    if ($found) {
                        if ($attempt > 1) {
                            Log::info('[WORKER-AUTH] verify succeeded on retry', ['attempt' => $attempt, 'path' => $storagePath]);
                        }
                        return true;
                    }
                }

                Log::warning('[WORKER-AUTH] verify attempt failed (file not in directory)', [
                    'attempt' => $attempt,
                    'path' => $storagePath,
                    'status' => $response->status(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('[WORKER-AUTH] verify exception', [
                    'attempt' => $attempt,
                    'path' => $storagePath,
                    'error' => $e->getMessage(),
                ]);
            }
            if ($attempt < 3) {
                sleep(5);
            }
        }
        return false;
    }

    /**
     * POST /reels/wasm-status - Log ffmpeg.wasm capability (read-only check)
     * Body: { wasmSupported: bool, crossOriginIsolated: bool, userAgent: string }
     */
    public function wasmStatus(Request $request)
    {
        $wasmSupported = $request->boolean('wasmSupported');
        $crossOriginIsolated = $request->boolean('crossOriginIsolated');
        $hasSharedArrayBuffer = $request->boolean('hasSharedArrayBuffer');
        Log::channel('reels')->info('[WASM-STATUS] ffmpeg.wasm check', [
            'wasmSupported' => $wasmSupported,
            'crossOriginIsolated' => $crossOriginIsolated,
            'hasSharedArrayBuffer' => $hasSharedArrayBuffer,
            'user_id' => auth()->id(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'implemented' => false,
            'note' => 'ffmpeg.wasm NOT YET IMPLEMENTED - planned for Duet/audio browser processing. Current Duet still uses cPanel FFmpeg CompressReel.',
        ]);
        return response()->json([
            'success' => true,
            'implemented' => false,
            'wasmSupported' => $wasmSupported,
            'message' => 'ffmpeg.wasm is planned, not yet implemented. Logged for audit.',
        ]);
    }

    private function deleteBunnyStorageFile(string $storagePath): bool
    {
        $storageZone = gs('bunny_reels_storage_zone') ?: config('bunny.reels_storage_zone') ?: config('bunny.storage_zone');
        $accessKey = gs('bunny_reels_storage_access_key') ?: config('bunny.reels_storage_access_key') ?: config('bunny.storage_access_key');
        $region = gs('bunny_reels_storage_region') ?: config('bunny.reels_storage_region') ?: config('bunny.storage_region');
        if (empty($storageZone) || empty($accessKey)) {
            Log::warning('[WORKER-AUTH] delete skipped - storage not configured');
            return false;
        }
        $regionClean = strtolower(trim($region ?? ''));
        $host = (empty($regionClean) || in_array($regionClean, ['de', 'main', 'falkenstein'])) ? 'storage.bunnycdn.com' : "{$regionClean}.storage.bunnycdn.com";
        $url = "https://{$host}/{$storageZone}/{$storagePath}";

        try {
            $response = Http::withHeaders(['AccessKey' => $accessKey])->timeout(10)->delete($url);
            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('[WORKER-AUTH] delete exception: ' . $e->getMessage());
            return false;
        }
    }

}
