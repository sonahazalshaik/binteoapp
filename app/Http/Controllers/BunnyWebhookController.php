<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BunnyWebhookController extends Controller
{
    /**
     * Handle incoming webhook events from Bunny Stream.
     *
     * Bunny sends a POST request with a JSON body containing:
     * - VideoId (guid)
     * - Status (int): 0 = Queued, 1 = Processing, 2 = Encoding, 3 = Finished, 4 = Error, 5 = UploadFailed
     * - VideoLibraryId
     *
     * Note: The exact status codes may vary. Refer to your Bunny.net docs.
     *       Common convention: Status 3 or "Finished" = ready.
     */
    public function handle(Request $request)
    {
        if ($request->isMethod('get')) {
            return response()->json([
                'success' => true,
                'message' => 'Bunny Webhook Endpoint. Active and listening for POST notifications.'
            ]);
        }

        // ── IP WHITELISTING (Bunny.net Official Ranges) ──
        $allowedIps = ['185.11.124.', '143.244.32.', '162.254.204.', '162.254.206.']; // Common prefixes
        $clientIp = $request->ip();
        
        $isWhitelisted = false;
        foreach ($allowedIps as $ipPrefix) {
            if (str_starts_with($clientIp, $ipPrefix)) {
                $isWhitelisted = true;
                break;
            }
        }

        // Allow local testing if needed
        if (app()->environment('local')) {
            $isWhitelisted = true;
        }

        if (!$isWhitelisted) {
            Log::warning('Bunny Webhook: Unauthorized IP attempt', ['ip' => $clientIp]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        Log::info('Bunny Webhook received', $request->all());

        $videoGuid = $request->input('VideoGuid') ?? $request->input('VideoId');
        $status = $request->input('Status');
        
        Log::info("[REEL-WEBHOOK] VideoGuid = {$videoGuid}, Status = {$status}");

        if (!$videoGuid) {
            Log::warning('Bunny Webhook: Missing VideoGuid/VideoId');
            return response()->json(['error' => 'Missing video identifier'], 400);
        }

        $model = \App\Models\Video::withoutGlobalScopes()->where('bunny_id', $videoGuid)->first();
        $isReel = false;
        $isPartB = false;
 
        if (!$model) {
            $model = \App\Models\Reel::withoutGlobalScopes()->where('bunny_id', $videoGuid)->first();
            $isReel = (bool)$model;
        }

        if (!$model) {
            $model = \App\Models\Reel::withoutGlobalScopes()->where('processing_bunny_id', $videoGuid)->first();
            if ($model) {
                $isReel = true;
                $isPartB = true;
            }
        }
 
        if (!$model) {
            Log::warning('Bunny Webhook: Asset not found in DB', ['bunny_id' => $videoGuid]);
            return response()->json(['success' => true, 'message' => 'Unknown asset logged and ignored']);
        }

        // ── Bunny Stream webhook status codes (as used by this integration) ──
        // 0 = Queued/Created | 1 = Uploaded/Processing | 2 = Encoding
        // 3 = Finished (source is ready to download) | 4 = Error | 5 = Upload failed
        // 6 = Incompatible asset | 7 = Finished (final ready state)
        $isReady = in_array($status, [3, 7], true);

        if ($isReady) {
            if ($isReel && !$isPartB && $this->reelNeedsProcessing($model)) {
                $claimed = $this->claimReelProcessing($model);

                if (!$claimed) {
                    Log::info('Bunny Webhook: Reel processing already claimed or completed; skipping duplicate', [
                        'reel_id' => $model->id,
                        'bunny_id' => $videoGuid,
                        'is_compressed' => (bool) $model->is_compressed,
                    ]);

                    return response()->json(['success' => true, 'message' => 'Already processing or completed']);
                } else {
                    \App\Jobs\CompressReel::dispatch($model);
                    Log::info('Bunny Webhook: Dispatched CompressReel for mixing audio', [
                        'reel_id' => $model->id,
                        'bunny_id' => $videoGuid,
                    ]);
                    return response()->json(['success' => true, 'message' => 'Dispatched audio mixing job']);
                }
            }

            // If it is Part B, perform strict atomic promotion database updates BEFORE deleting Part A.
            if ($isReel && $isPartB) {
                $originalBunnyId = $model->bunny_id;
                
                $updateData = [
                    'bunny_id' => $videoGuid,
                    'processing_bunny_id' => null,
                    'is_compressed' => true,
                    'compression_status' => 2,
                    'bunny_status' => 'ready',
                ];

                if ($model->status != 'draft' && $model->status != \App\Constants\Status::DRAFT) {
                    $updateData['status'] = \App\Constants\Status::PUBLISHED;
                }

                $succeeded = $model->update($updateData);

                if ($succeeded) {
                    Log::info('Bunny Webhook: Part B promoted in database successfully.', [
                        'reel_id' => $model->id,
                        'new_bunny_id' => $videoGuid,
                        'old_bunny_id' => $originalBunnyId,
                    ]);

                    if ($originalBunnyId && $originalBunnyId !== $videoGuid) {
                        try {
                            app(\App\Services\BunnyStreamService::class)->useReelLibrary()->deleteVideo($originalBunnyId);
                            Log::info('Bunny Webhook: Deleted original Part A video asset from Bunny CDN.', [
                                'original_bunny_id' => $originalBunnyId,
                            ]);
                        } catch (\Throwable $e) {
                            Log::error('Bunny Webhook: Failed to delete original Part A video asset from Bunny CDN.', [
                                'original_bunny_id' => $originalBunnyId,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                } else {
                    Log::error('Bunny Webhook: Database update failed for promoting Part B. Part A was NOT deleted.', [
                        'reel_id' => $model->id,
                        'bunny_id' => $videoGuid,
                    ]);
                    return response()->json(['error' => 'Database update failed'], 500);
                }
            } else {
                // Normal Reel or Video (Standard flow) OR New cPanel Reel pipeline finalization
                $updateData = ['bunny_status' => 'ready'];

                if ($isReel) {
                    $updateData['compression_status'] = 2;
                }

                if ($model->status != 'draft' && $model->status != \App\Constants\Status::DRAFT) {
                    $updateData['status'] = $isReel ? \App\Constants\Status::PUBLISHED : 'published';
                }

                $model->update($updateData);

                Log::info('[REEL-BUNNY] READY', [
                    'id' => $model->id,
                    'is_reel' => $isReel,
                    'bunny_id' => $videoGuid,
                ]);

                if ($isReel) {
                    try {
                        $tempDir = storage_path("app/reels/temp/{$model->id}");
                        if (file_exists($tempDir)) {
                            array_map('unlink', glob("$tempDir/*"));
                            @rmdir($tempDir);
                            Log::info('[REEL-CLEANUP] Temporary files deleted', ['reel_id' => $model->id]);
                        }
                    } catch (\Throwable $e) {
                        Log::warning('[REEL-CLEANUP] Failed to delete temp files: ' . $e->getMessage());
                    }
                }
            }

            // Notify the owner via broadcast if available
            $url = $isReel ? route('reels.show', $model->slug) : route('videos.show', $model->slug);
            $typeStr = $isReel ? 'reel' : 'video';

            try {
                broadcast(new \App\Events\UserNotification(
                    $model->user_id,
                    'Your ' . $typeStr . ' "' . $model->title . '" has finished processing and is now live!',
                    $url
                ));
            } catch (\Throwable $e) {
                Log::warning('Bunny Webhook: Owner broadcast skipped', ['error' => $e->getMessage()]);
            }

            Log::info('[NOTIFY-PIPELINE] Bunny Webhook: Encoding complete. Subscriber notifications already dispatched at upload time.', [
                'asset_id' => $model->id,
                'type' => $typeStr,
            ]);
        } elseif (in_array($status, [0, 1, 2], true)) {
            // Queued / Uploading / Encoding — the source is NOT ready yet.
            $model->update([
                'bunny_status' => 'processing',
            ]);

            Log::info('Bunny Webhook: Asset still queued/processing', [
                'id' => $model->id,
                'is_reel' => $isReel,
                'bunny_id' => $videoGuid,
                'status' => $status,
            ]);
        } elseif (in_array($status, [4, 5, 6], true)) {
            // Processing failed / upload failed / incompatible
            $updateData = [
                'bunny_status' => 'error',
            ];
            if ($isReel) {
                $updateData['compression_status'] = 3;
            }
            $model->update($updateData);

            Log::error('Bunny Webhook: Asset processing failed', [
                'id' => $model->id,
                'is_reel' => $isReel,
                'bunny_id' => $videoGuid,
                'status' => $status,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Determine whether a reel still requires audio mixing / compression.
     *
     * A reel needs processing when it has music selected OR it is a duet
     * (parent reel). A duet reel with music_source = "none" still needs
     * processing (to stack the duet videos) but does NOT require a music file.
     */
    private function reelNeedsProcessing($model): bool
    {
        if (!$model instanceof \App\Models\Reel) {
            return false;
        }
        if ((bool) $model->is_compressed) {
            return false;
        }
        // Worker Storage reels never need server FFmpeg
        if (!empty($model->storage_path)) {
            return false;
        }
        // If compression_status is 4 (uploading final video), 5 (Bunny processing final video) or 2 (completed), it does not need processing
        if (in_array((int)$model->compression_status, [2, 4, 5])) {
            return false;
        }
        return (($model->music_source && $model->music_source !== 'none') || $model->parent_id);
    }

    /**
     * Atomically claim a reel for processing.
     *
     * Sets compression_status to 1 (processing) ONLY if the reel is not already
     * compressed and not already claimed. Because the UPDATE is conditional and
     * atomic, two concurrent webhook deliveries cannot both claim the same reel.
     *
     * Returns true only for the caller that won the claim.
     */
    private function claimReelProcessing($model): bool
    {
        if (!$model instanceof \App\Models\Reel) {
            return false;
        }

        $affected = \App\Models\Reel::withoutGlobalScopes()
            ->where('id', $model->id)
            ->where('is_compressed', false)
            ->where(function ($q) {
                $q->whereNull('compression_status')->orWhere('compression_status', '!=', 1);
            })
            ->update([
                'bunny_status' => 'processing',
                'compression_status' => 1,
            ]);

        return (bool) $affected;
    }
}
