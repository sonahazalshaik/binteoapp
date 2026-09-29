<?php

namespace App\Services\Frontend;

use App\Models\Video;
use App\Models\Reel;
use App\Models\ReelHashtag;
use App\Models\ReelTag;
use App\Models\ReelMention;
use App\Models\ReelMusic;
use App\Services\BunnyStreamService;
use App\Constants\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UploadService
{
    public function __construct(
        protected BunnyStreamService $bunny
    ) {}

    public function logDraftEvent(string $event, array $data = [], string $level = 'info'): void
    {
        $context = array_merge([
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $data);

        unset($context['access_key'], $context['accessKey'], $context['apiKey'], $context['token'], $context['password'], $context['secret']);

        try {
            Log::channel('draft_uploads')->$level("[DRAFT-LIFECYCLE] {$event}", $context);
        } catch (\Exception $e) {
            Log::$level("[DRAFT-LIFECYCLE] {$event}", $context);
        }
    }

    public function prepareUpload(Request $request): array
    {
        Log::info('BunnyUploadController: Preparing Video Upload', [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'draft_id' => $request->draft_id,
            'origin' => $request->header('origin'),
            'user_agent' => $request->userAgent()
        ]);
        
        $video = null;
        if ($request->has('draft_id')) {
            $video = Video::where('id', $request->draft_id)->where('user_id', auth()->id())->first();
        }

        if (!$video) {
            $video = Video::where('user_id', auth()->id())
                ->where('status', 'draft')
                ->where('created_at', '>=', now()->subSeconds(60))
                ->latest()
                ->first();
        }
        
        if ($video && in_array($video->status, [\App\Constants\Status::ACTIVE, 'processing'])) {
            abort(400, 'This video is already published or processing.');
        }

        if (!$video || !$video->bunny_id) {
            $this->bunny->useVideoLibrary();
            $bunnyResponse = $this->bunny->createVideo($request->title, $this->bunny->getVideoCollectionId());
            $bunnyId = $bunnyResponse['guid'];
        } else {
            $bunnyId = $video->bunny_id;
        }

        if (!$video) {
            $video = new Video();
            $video->user_id = auth()->id();
            
            $baseSlug = Str::slug($request->title ?: 'video');
            $slug = $baseSlug;
            while (Video::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . Str::random(5);
            }
            $video->slug = $slug;
        }

        $video->title = $request->title;
        $video->description = $request->description;
        $video->video_path = '';
        $video->bunny_id = $bunnyId;
        if (!in_array($video->bunny_status, ['ready', 'encoding', 'processing'])) {
            $video->bunny_status = 'uploading';
        }
        $isExplicitPublish = (!$request->has('is_draft') || $request->is_draft === '0' || $request->is_draft === 0 || $request->is_draft === false || $request->is_draft === 'false');

        if ($isExplicitPublish) {
            $video->status = Status::PUBLISHED;
        } else {
            if (!$video->exists || in_array($video->status, ['draft', Status::DRAFT, 0, '0'])) {
                $video->status = Status::DRAFT;
            }
        }
        $video->visibility = $request->visibility ?? Status::PUBLIC;
        $video->location = $request->location;
        $video->language = $request->language;
        $video->is_age_restricted = $request->is_age_restricted ?? false;
        $video->duration = $request->duration;

        if ($request->schedule_video == '1' && $request->schedule_date && $request->schedule_time) {
            $video->scheduled_at = Carbon::parse($request->schedule_date . ' ' . $request->schedule_time, 'Asia/Kolkata')->setTimezone(config('app.timezone'));
        }

        $tierMap = ['free' => 0, 'premium' => 1, 'exclusive' => 2];
        $pricingTierStr = $request->pricing_tier ?? 'free';
        $pricingTierInt = $tierMap[$pricingTierStr] ?? 0;
        $video->pricing_tier = $pricingTierInt;
        $video->is_premium = (in_array($pricingTierStr, ['premium', 'exclusive']) && auth()->user()->hasPremiumAccess()) ? 1 : 0;
        $video->price = $video->is_premium ? ($request->price ?? 0) : 0;

        if (is_null($video->video_path)) {
            $video->video_path = '';
        }
        if (is_null($video->thumbnail_path)) {
            $video->thumbnail_path = '';
        }

        if ($request->category_id) {
            $video->category_id = $request->category_id;
        }
        $video->save();
        if ($request->category_id) {
            $video->categories()->sync([$request->category_id]);
        }

        if ($request->tags) {
            $tags = is_array($request->tags) ? $request->tags : json_decode($request->tags, true);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if (trim($tag)) {
                        $video->tags()->create(['tag' => trim($tag)]);
                    }
                }
            }
        }

        $this->bunny->useVideoLibrary();
        $tusParams = $this->bunny->getTusUploadParams($bunnyId);

        if ($isExplicitPublish) {
            if ($video->bunny_status === 'ready') {
                try {
                    $this->publishVideo($video);
                } catch (\Exception $e) {
                    Log::info('prepareUpload publishVideo notice: ' . $e->getMessage());
                }
            } else {
                $video->status = Status::PUBLISHED;
                $video->save();
            }
        } else {
            \App\Models\AdminNotification::create([
                'user_id' => auth()->id(),
                'title' => 'New video draft saved: ' . $video->title,
                'click_url' => route('admin.videos.index'),
            ]);
        }

        Log::info('BunnyUploadController: Video TUS Params Generated', [
            'video_id' => $video->id,
            'bunny_id' => $bunnyId,
            'tus_endpoint' => $tusParams['endpoint'],
            'library_id' => $tusParams['headers']['LibraryId'],
            'signature_preview' => substr($tusParams['headers']['AuthorizationSignature'], 0, 10) . '...'
        ]);

        return [
            'success' => true,
            'video_id' => $video->id,
            'video_slug' => $video->slug,
            'bunny_id' => $bunnyId,
            'tus' => $tusParams,
            'direct' => [
                'url' => "https://video.bunnycdn.com/library/{$tusParams['headers']['LibraryId']}/videos/{$bunnyId}",
                'access_key' => $this->bunny->getApiKey()
            ]
        ];
    }

    public function mapBunnyStatus(array $bunnyVideoInfo): array
    {
        if (empty($bunnyVideoInfo)) {
            return [
                'bunny_status' => 'error',
                'status_code' => 404,
                'encode_progress' => 100,
                'is_ready' => false,
                'message' => 'Video not found on Bunny'
            ];
        }

        $statusCode = (int)($bunnyVideoInfo['status'] ?? -1);
        $encodeProgress = (int)($bunnyVideoInfo['encodeProgress'] ?? 0);

        // Status 3 = Finished/Ready, 4 = Preserved
        if ($statusCode === 3 || $statusCode === 4 || $encodeProgress >= 100) {
            $status = 'ready';
            $isReady = true;
        } elseif ($statusCode === 1 || $statusCode === 2) {
            $status = 'processing';
            $isReady = false;
        } elseif ($statusCode === 5 || $statusCode === 6) {
            $status = 'error';
            $isReady = false;
        } else {
            $status = 'uploading';
            $isReady = false;
        }

        return [
            'bunny_status' => $status,
            'status_code' => $statusCode,
            'encode_progress' => $isReady ? 100 : $encodeProgress,
            'is_ready' => $isReady,
            'message' => $isReady ? 'Video is ready!' : ($status === 'error' ? 'Processing failed' : 'Still processing...')
        ];
    }

    public function publishVideo(Video $video): array
    {
        return \Illuminate\Support\Facades\Cache::lock('publish_video_' . $video->id, 10)->block(5, function () use ($video) {
            $wasDraft = in_array($video->status, [Status::DRAFT, 'draft', 0, '0']);

            $this->logDraftEvent('publish_attempt', [
                'draft_id' => $video->id,
                'bunny_id' => $video->bunny_id,
                'bunny_status' => $video->bunny_status,
                'current_status' => $video->status,
                'was_draft' => $wasDraft
            ]);

            // Authoritative Bunny Stream readiness check if bunny_id exists
            if ($video->isBunnyVideo() && $video->bunny_id) {
                $mapped = ['is_ready' => false];
                try {
                    $this->bunny->useVideoLibrary();
                    $bunnyVideoInfo = $this->bunny->getVideo($video->bunny_id);
                    $mapped = $this->mapBunnyStatus($bunnyVideoInfo);
                    
                    $video->bunny_status = $mapped['bunny_status'];
                    if (!empty($bunnyVideoInfo['length'])) {
                        $video->duration = gmdate('H:i:s', (int)$bunnyVideoInfo['length']);
                    }
                    $video->save();
                } catch (\Exception $e) {
                    Log::warn('Could not fetch Bunny video status during publish check: ' . $e->getMessage());
                }

                // Authoritative Safeguard: Reject publish if media is not BUNNY_READY (Status 3/4 or encodeProgress >= 100)
                if (!$mapped['is_ready'] || $video->bunny_status !== 'ready') {
                    abort(400, 'Cannot publish video. Media is still uploading, processing, or status is unknown.');
                }
            }

            // Canonical published status assignment using Status::PUBLISHED (1)
            $video->status = Status::PUBLISHED;
            if ($video->visibility === 'draft') {
                $video->visibility = Status::PUBLIC;
            }
            $video->save();

            // Idempotent subscriber notification: Notify ONCE when transitioning from draft to published
            if ($wasDraft) {
                $this->notifySubscribersVideo($video);

                \App\Models\AdminNotification::create([
                    'user_id' => auth()->id(),
                    'title' => 'New video published: ' . $video->title,
                    'click_url' => route('admin.videos.index'),
                ]);
            }

            $this->logDraftEvent('publish_success', [
                'draft_id' => $video->id,
                'bunny_id' => $video->bunny_id,
                'new_status' => Status::PUBLISHED,
                'subscriber_notification_sent' => $wasDraft
            ]);

            return [
                'success' => true,
                'message' => 'Video published successfully!',
                'video' => $video
            ];
        });
    }

    public function autoSaveDraft(Request $request): array
    {
        $draftId = $request->input('draft_id');
        $video = null;

        if ($draftId) {
            $video = Video::where('id', $draftId)->where('user_id', auth()->id())->first();
        }

        if (!$video) {
            // Deduplicate concurrent auto-save draft requests
            $video = Video::where('user_id', auth()->id())
                ->where('status', 'draft')
                ->where('created_at', '>=', now()->subSeconds(30))
                ->latest()
                ->first();
        }

        if (!$video) {
            $video = new Video();
            $video->user_id = auth()->id();
            
            $baseSlug = Str::slug($request->title ?: 'draft-video');
            $slug = $baseSlug;
            while (Video::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . Str::random(5);
            }
            $video->slug = $slug;
            $video->video_path = '';
            $video->thumbnail_path = '';
            $video->bunny_status = 'uploading';
            $video->status = Status::DRAFT;
        }

        if (!$video->bunny_id) {
            try {
                $this->bunny->useVideoLibrary();
                $bunnyResponse = $this->bunny->createVideo($request->title ?: 'Untitled Draft', $this->bunny->getVideoCollectionId());
                if (!empty($bunnyResponse['guid'])) {
                    $video->bunny_id = $bunnyResponse['guid'];
                }
            } catch (\Exception $e) {
                Log::error('Failed to create Bunny video in autoSaveDraft: ' . $e->getMessage());
            }
        }

        $video->title = $request->title ?: 'Untitled Draft';
        $video->description = $request->description;
        $video->visibility = $request->visibility ?? Status::PUBLIC;
        $video->location = $request->location;
        $video->language = $request->language;
        $video->is_age_restricted = $request->boolean('is_age_restricted', false);
        
        $tierMap = ['free' => 0, 'premium' => 1, 'exclusive' => 2];
        $pricingTierStr = $request->pricing_tier ?? 'free';
        $pricingTierInt = $tierMap[$pricingTierStr] ?? 0;
        $video->pricing_tier = $pricingTierInt;
        $video->is_premium = (in_array($pricingTierStr, ['premium', 'exclusive']) && auth()->user()->hasPremiumAccess()) ? 1 : 0;
        $video->price = $video->is_premium ? ($request->price ?? 0) : 0;

        if ($request->filled('bunny_status') && in_array($request->bunny_status, ['uploading', 'processing', 'encoding', 'ready', 'error'])) {
            $video->bunny_status = $request->bunny_status;
        }

        if ($request->boolean('reset_video', false)) {
            $video->bunny_id = null;
            $video->video_path = '';
            $video->thumbnail_path = '';
            $video->bunny_status = 'uploading';
            $video->status = 'draft';
        }

        if (is_null($video->video_path)) {
            $video->video_path = '';
        }
        if (is_null($video->thumbnail_path)) {
            $video->thumbnail_path = '';
        }

        if ($request->category_id) {
            $video->category_id = $request->category_id;
        }

        $isNewDraft = !$video->exists;
        $video->save();

        $this->logDraftEvent($isNewDraft ? 'draft_created' : 'draft_metadata_updated', [
            'draft_id' => $video->id,
            'bunny_id' => $video->bunny_id,
            'title' => $video->title,
            'bunny_status' => $video->bunny_status,
            'status' => $video->status,
            'pricing_tier' => $pricingTierStr,
        ]);

        if ($request->category_id) {
            $video->categories()->sync([$request->category_id]);
        }

        $tusParams = null;
        $directParams = null;
        if ($video->bunny_id) {
            try {
                $this->bunny->useVideoLibrary();
                $tusParams = $this->bunny->getTusUploadParams($video->bunny_id);
                $directParams = [
                    'url' => "https://video.bunnycdn.com/library/{$tusParams['headers']['LibraryId']}/videos/{$video->bunny_id}",
                    'access_key' => $this->bunny->getApiKey()
                ];
            } catch (\Exception $e) {
                Log::error('Failed to get upload params in autoSaveDraft: ' . $e->getMessage());
            }
        }

        return [
            'success' => true,
            'draft_id' => $video->id,
            'slug' => $video->slug,
            'bunny_id' => $video->bunny_id,
            'bunny_status' => $video->bunny_status,
            'tus' => $tusParams,
            'direct' => $directParams,
        ];
    }

    public function uploadThumbnail(Request $request, Video $video): array
    {
        if (auth()->id() != $video->user_id) {
            return ['error' => 'Unauthorized'];
        }

        $thumbnailPath = fileUploader($request->file('thumbnail'), getFilePath('thumbnail'));
        $video->update(['thumbnail_path' => $thumbnailPath]);

        return [
            'success' => true,
            'thumbnail_url' => asset(getFilePath('thumbnail') . '/' . $thumbnailPath),
        ];
    }

    public function checkStatus(Video $video): array
    {
        $isPublic = ($video->visibility == Status::PUBLIC);
        $isOwner = (auth()->check() && auth()->id() == $video->user_id);
        $isAdmin = auth()->guard('admin')->check();

        if (!$isPublic && !$isOwner && !$isAdmin) {
            return ['error' => 'Unauthorized'];
        }

        if (!$video->bunny_id) {
            return ['error' => 'Not a Bunny Stream video'];
        }

        try {
            $this->bunny->useVideoLibrary();
            $bunnyVideo = $this->bunny->getVideo($video->bunny_id);
            $mapped = $this->mapBunnyStatus($bunnyVideo);

            $video->update(['bunny_status' => $mapped['bunny_status']]);

            if ($mapped['bunny_status'] === 'ready' && $video->status == Status::PUBLISHED) {
                try {
                    $this->publishVideo($video);
                } catch (\Exception $e) {
                    Log::info('checkStatus publishVideo notice: ' . $e->getMessage());
                }
            }

            return [
                'success' => true,
                'bunny_status' => $video->bunny_status,
                'status_code' => $mapped['status_code'],
                'encode_progress' => $mapped['encode_progress'],
                'message' => $mapped['message']
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function prepareReelUpload(Request $request): array
    {
        Log::info('UploadService: Preparing Reel Upload', [
            'user_id' => auth()->id(),
            'title' => $request->title,
        ]);

        $bunnyId = null; // New reels bypass Bunny Stream asset creation completely

        $slug = Str::slug($request->title);
        if (Reel::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::random(6);
        }

        $reel = new Reel();
        $reel->user_id = auth()->id();
        $reel->title = $request->title;
        $reel->slug = $slug;
        $reel->description = $request->description;
        $reel->video_path = '';
        $reel->bunny_id = $bunnyId;
        $reel->bunny_status = null; // NULL during cPanel upload
        $reel->compression_status = 0; // queued
        $reel->status = $request->boolean('is_draft') ? Status::DRAFT : Status::PUBLISHED;
        $reel->visibility = $request->visibility ?? Status::PUBLIC;
        $reel->location = $request->location;
        $reel->language = $request->language;
        $reel->category_id = $request->category_id;
        $reel->duration = $request->duration;

        $reel->allow_comments = $request->has('allow_comments') ? $request->boolean('allow_comments') : true;
        $reel->is_age_restricted = $request->has('is_age_restricted') ? $request->boolean('is_age_restricted') : false;
        $reel->allow_duet = $request->has('allow_duet') ? $request->boolean('allow_duet') : true;
        $reel->allow_stitch = $request->has('allow_stitch') ? $request->boolean('allow_stitch') : true;

        if (in_array($request->music_source, ['global', 'original', 'upload'])) {
            $reel->music_id = null;
        } else {
            $reel->music_id = null;
            if ($request->music_id) {
                $music = ReelMusic::where('id', $request->music_id)->orWhere('slug', $request->music_id)->first();
                if ($music) {
                    $reel->music_id = $music->id;
                    $music->increment('usage_count');
                }
            }
        }
        $reel->music_source = $request->music_source;
        $reel->music_start_time = $request->music_start_time ?? 0;

        if ($request->parent_id) {
            $parentReel = Reel::where('id', $request->parent_id)->orWhere('slug', $request->parent_id)->first();
            $reel->parent_id = $parentReel ? $parentReel->id : null;
        }
        $reel->is_duet = $reel->parent_id ? 1 : 0;

        if ($request->original_reel_id) {
            $originalReel = Reel::where('id', $request->original_reel_id)->orWhere('slug', $request->original_reel_id)->first();
            $reel->original_reel_id = $originalReel ? $originalReel->id : null;
        }

        $reel->music_volume = $request->music_volume ?? 1.0;
        $reel->mic_volume = $request->mic_volume ?? 1.0;

        if (in_array($request->music_source, ['global', 'original'])) {
            $reel->global_music_url = $request->global_music_url;
            $reel->global_music_title = $request->global_music_title;
            $reel->global_music_artist = $request->global_music_artist;
            // Strip query string from thumbnail to prevent SQL 1406 Data too long error for signed URLs
            $thumbnail = $request->global_music_thumbnail;
            $reel->global_music_thumbnail = $thumbnail ? strtok($thumbnail, '?') : null;
            $reel->audio_name = $request->global_music_title;
        } elseif ($request->music_source == 'upload' && $request->hasFile('music_file')) {
            try {
                $audioPath = fileUploader($request->music_file, getFilePath('reelMusic'));
                $reel->audio_path = $audioPath;
                $reel->audio_name = $request->audio_name ?? $request->file('music_file')->getClientOriginalName();
            } catch (\Exception $e) {
                Log::error('UploadService: Could not upload audio file: ' . $e->getMessage());
            }
        }

        $reel->save();

        $hashtags = Reel::extractHashtags($request->description);
        if ($request->has('hashtags')) {
            $inputHashtags = is_array($request->hashtags) ? $request->hashtags : json_decode($request->hashtags, true);
            if (is_array($inputHashtags)) {
                foreach ($inputHashtags as $tag) {
                    $tag = ltrim(trim($tag), '#');
                    if ($tag && !in_array($tag, $hashtags)) {
                        $hashtags[] = $tag;
                    }
                }
            }
        }
        foreach ($hashtags as $tag) {
            ReelHashtag::create(['reel_id' => $reel->id, 'hashtag' => $tag]);
            ReelTag::create(['reel_id' => $reel->id, 'tag' => $tag]);
        }

        foreach (Reel::extractMentions($request->description) as $username) {
            $mentionedUser = \App\Models\User::where('username', $username)->first();
            if ($mentionedUser) {
                ReelMention::create([
                    'reel_id' => $reel->id,
                    'mentioned_user_id' => $mentionedUser->id,
                    'source' => 'description',
                ]);
            }
        }

        if (!$request->is_draft) {
            $this->notifySubscribersReel($reel);
        }

        // IMPORTANT — Honoring the existing frontend contract:
        // The reel page JS ALWAYS reads prepData.tus.endpoint / prepData.tus.headers
        // (in the desktop TUS branch) and prepData.direct.url / prepData.direct.access_key
        // (in the Android branch). We MUST NOT return null for any of these, or the
        // frontend throws  `Cannot read properties of null (reading 'endpoint')`.
        //
        // Reels DO NOT use TUS. The reel upload page has been updated so reels upload
        // via the server-side NON-TUS endpoint `reels/direct_upload` (which streams to
        // disk and keeps the local source for CompressReel), and it no longer reads
        // prepData.tus in the active path. We still return a well-formed `tus`/`direct`
        // object here purely to preserve the contract shape (safe, unused by reels).
        // Normal video TUS (prepareUpload) is unchanged.
        $libraryId = gs('bunny_reel_library_id') ?: config('bunny.library_id');
        $apiKey = $this->bunny->getApiKey();

        if ($bunnyId) {
            $this->bunny->useReelLibrary();
            $tusParams = $this->bunny->getTusUploadParams($bunnyId);
            $directUrl = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$bunnyId}";
        } else {
            $tusParams = [
                'endpoint' => 'https://video.bunnycdn.com/tusupload',
                'headers' => [
                    'LibraryId' => $libraryId,
                    'AccessKey' => $apiKey,
                ]
            ];
            $directUrl = "";
        }

        Log::info('[REEL-UPLOAD] session prepared', [
            'reel_id' => $reel->id,
            'bunny_id' => $bunnyId,
            'transport' => 'direct',
        ]);

        return [
            'success' => true,
            'reel_id' => $reel->id,
            'reel_slug' => $reel->slug,
            'bunny_id' => $bunnyId,
            'transport' => 'direct',
            'tus' => $tusParams,
            'direct' => [
                'url' => $directUrl,
            ],
        ];
    }

    public function uploadReelThumbnail(Request $request, Reel $reel): array
    {
        if (auth()->id() != $reel->user_id) {
            return ['error' => 'Unauthorized'];
        }

        $thumbnailPath = fileUploader($request->file('thumbnail'), getFilePath('reelThumbnail'));
        $reel->update(['thumbnail_path' => $thumbnailPath]);

        // Dedicated reels log - thumbnail generation audit (Bunny Storage has no auto-thumb)
        Log::channel('reels')->info('[REEL-THUMB] uploaded', [
            'reel_id' => $reel->id,
            'path' => $thumbnailPath,
            'storage_path' => $reel->storage_path,
            'has_storage' => !empty($reel->storage_path),
            'has_bunny_id' => !empty($reel->bunny_id),
            'generated' => 'manual/browser-canvas',
        ]);
        Log::info('[REEL-THUMB] thumbnail saved', ['reel_id' => $reel->id, 'path' => $thumbnailPath]);

        return [
            'success' => true,
            'thumbnail_url' => asset(getFilePath('reelThumbnail') . '/' . $thumbnailPath),
        ];
    }

    public function checkReelStatus(Reel $reel): array
    {
        Log::info('[REEL-POLL-BACKEND] checkReelStatus hit', [
            'reel_id' => $reel->id,
            'bunny_id' => $reel->bunny_id,
            'bunny_status' => $reel->bunny_status,
            'compression_status' => $reel->compression_status,
            'processing_bunny_id' => $reel->processing_bunny_id,
            'user_id' => auth()->id(),
        ]);

        $isPublic = ($reel->visibility == Status::PUBLIC);
        $isOwner = (auth()->check() && auth()->id() == $reel->user_id);
        $isAdmin = auth()->guard('admin')->check();

        if (!$isPublic && !$isOwner && !$isAdmin) {
            Log::warning('[REEL-POLL-BACKEND] Unauthorized access attempt', [
                'reel_id' => $reel->id,
                'user_id' => auth()->id(),
            ]);
            return ['error' => 'Unauthorized'];
        }

        $isMixingReel = (bool)($reel->is_duet || ($reel->music_source && $reel->music_source !== 'none'));

        if (empty($reel->bunny_id) || !empty($reel->storage_path)) {
            $compStatus = (int)($reel->compression_status ?? 0);
            $stage = 'queued';
            $message = 'Waiting for processing...';
            $bunnyStatus = 'processing';
            $progress = 0;

            if ($compStatus === 1) {
                $stage = 'mixing';
                $message = 'Mixing audio...';
            } elseif ($compStatus === 4) {
                $stage = 'uploading_to_bunny';
                $message = 'Uploading final video...';
            } elseif ($compStatus === 2) {
                $stage = 'ready';
                $message = 'Ready';
                $bunnyStatus = 'ready';
                $progress = 100;
            } elseif ($compStatus === 3) {
                $stage = 'failed';
                $message = 'Processing failed';
                $bunnyStatus = 'failed';
                $progress = 100;
            }

            return [
                'success' => true,
                'bunny_status' => $bunnyStatus,
                'status_code' => $compStatus,
                'encode_progress' => $progress,
                'compression_status' => $compStatus,
                'is_mixing_reel' => true,
                'stage' => $stage,
                'message' => $message
            ];
        }

        if ($reel->bunny_status === 'ready') {
            return [
                'success' => true,
                'status' => 'ready',
                'bunny_status' => 'ready',
                'encode_progress' => 100,
                'is_mixing_reel' => $isMixingReel,
                'compression_status' => $reel->compression_status,
                'stage' => 'ready'
            ];
        }

        try {
            $queryBunnyId = $reel->processing_bunny_id ?: $reel->bunny_id;
            Log::info("[REEL-POLL] Checking Bunny GUID = {$queryBunnyId}");
            $this->bunny->useReelLibrary();
            $bunnyVideo = $this->bunny->getVideo($queryBunnyId);
            $bunnyStatus = $bunnyVideo['status'] ?? -1;
            Log::info("[REEL-POLL] Bunny status = {$bunnyStatus}");

            // Asset missing on Bunny (404 -> getVideo returns []): stop polling forever.
            if (empty($bunnyVideo)) {
                $reel->update(['bunny_status' => 'error']);
                Log::warning('[BUNNY-POLL] Bunny asset not found; marking reel failed', [
                    'reel_id' => $reel->id,
                    'bunny_id' => $queryBunnyId,
                ]);
                return [
                    'success' => true,
                    'bunny_status' => 'error',
                    'status_code' => 404,
                    'encode_progress' => 0,
                    'stage' => 'failed',
                    'message' => 'Reel not found on Bunny'
                ];
            }

            $compStatus = (int)($reel->compression_status ?? 0);
            $stage = ($compStatus === 5) ? 'bunny_processing' : 'processing';

            if (in_array($bunnyStatus, [3, 7], true) || ($bunnyVideo['encodeProgress'] ?? 0) >= 100) {
                $stage = 'ready';
                if ($isMixingReel) {
                    if ($reel->processing_bunny_id) {
                        // Legacy Reel Part B finalized
                        $originalBunnyId = $reel->bunny_id;
                        $reel->update([
                            'bunny_id' => $queryBunnyId,
                            'processing_bunny_id' => null,
                            'is_compressed' => true,
                            'compression_status' => 2,
                            'bunny_status' => 'ready',
                        ]);
                        if ($reel->status != Status::DRAFT) {
                            $reel->update(['status' => Status::PUBLISHED]);
                        }
                        if ($originalBunnyId && $originalBunnyId !== $queryBunnyId) {
                            try { $this->bunny->deleteVideo($originalBunnyId); } catch (\Throwable $ignored) {}
                        }
                    } else {
                        // New cPanel pipeline finalization (Self-healing)
                        $reel->update([
                            'bunny_status' => 'ready',
                            'compression_status' => 2,
                        ]);
                        if ($reel->status != Status::DRAFT) {
                            $reel->update(['status' => Status::PUBLISHED]);
                        }
                        // Delete cPanel temporary files
                        try {
                            $tempDir = storage_path("app/reels/temp/{$reel->id}");
                            if (file_exists($tempDir)) {
                                array_map('unlink', glob("$tempDir/*"));
                                @rmdir($tempDir);
                                Log::info('[REEL-CLEANUP] Temporary files deleted (self-healed)', ['reel_id' => $reel->id]);
                            }
                        } catch (\Throwable $e) {
                            Log::warning('[REEL-CLEANUP] Failed to delete temp files: ' . $e->getMessage());
                        }
                    }

                    if ($reel->bunny_status !== 'ready') {
                        $this->notifySubscribersReel($reel);
                    }
                } else {
                    if ($reel->bunny_status !== 'ready') {
                        $this->notifySubscribersReel($reel);
                    }
                    $updateData = ['bunny_status' => 'ready'];
                    if ($reel->status != Status::DRAFT) {
                        $updateData['status'] = Status::PUBLISHED;
                    }
                    $reel->update($updateData);
                }
            } elseif (in_array($bunnyStatus, [0, 1, 2], true)) {
                if ($reel->bunny_status !== 'processing') {
                    $reel->update(['bunny_status' => 'processing']);
                }
            } elseif (in_array($bunnyStatus, [4, 5, 6], true)) {
                $reel->update(['bunny_status' => 'error']);
                $stage = 'failed';
            }

            // Standardize compression_status returned to frontend polling to correctly manage overlay visibility
            $returnedCompStatus = (int)($reel->compression_status ?? 0);
            if ($isMixingReel) {
                if ($reel->processing_bunny_id) {
                    $returnedCompStatus = 3;
                } elseif ($returnedCompStatus === 0) {
                    $returnedCompStatus = 3;
                }
            }

            return [
                'success' => true,
                'bunny_status' => $reel->bunny_status,
                'status_code' => $bunnyStatus,
                'encode_progress' => $bunnyVideo['encodeProgress'] ?? 0,
                'compression_status' => $returnedCompStatus,
                'is_mixing_reel' => $isMixingReel,
                'stage' => $stage,
                'message' => $reel->bunny_status === 'ready' ? 'Reel is ready!' : 'Still processing...'
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function notifySubscribersVideo(Video $video): void
    {
        try {
            $user = $video->user;
            if (!$user->channel) {
                Log::info('[NOTIFY-PIPELINE][STAGE-1] Video #{id} — user has no channel, skipping notification.', ['id' => $video->id]);
                return;
            }

            $channelName = $user->channel->name ?? $user->username;

            Log::info('[NOTIFY-PIPELINE][STAGE-1] Dispatching NotifySubscribers job.', [
                'trigger' => 'video',
                'video_id' => $video->id,
                'video_title' => $video->title,
                'channel' => $channelName,
                'user_id' => $user->id,
            ]);

            \App\Jobs\NotifySubscribers::dispatch(
                $user->id,
                $channelName,
                $channelName . ' uploaded a new video: ' . $video->title,
                route('videos.show', $video->slug),
                $channelName . ' uploaded a new video!',
                'video'
            );

            Log::info('[NOTIFY-PIPELINE][STAGE-1] NotifySubscribers job dispatched to queue successfully.');
        } catch (\Exception $e) {
            Log::error('[NOTIFY-PIPELINE][STAGE-1][ERROR] Could not dispatch video notifications: ' . $e->getMessage());
        }
    }

    private function notifySubscribersReel(Reel $reel): void
    {
        try {
            $user = $reel->user;
            if (!$user->channel) {
                Log::info('[NOTIFY-PIPELINE][STAGE-1] Reel #{id} — user has no channel, skipping notification.', ['id' => $reel->id]);
                return;
            }

            $channelName = $user->channel->name ?? $user->username;

            Log::info('[NOTIFY-PIPELINE][STAGE-1] Dispatching NotifySubscribers job.', [
                'trigger' => 'reel',
                'reel_id' => $reel->id,
                'reel_title' => $reel->title,
                'channel' => $channelName,
                'user_id' => $user->id,
            ]);

            \App\Jobs\NotifySubscribers::dispatch(
                $user->id,
                $channelName,
                $channelName . ' uploaded a new reel: ' . $reel->title,
                route('reels.show', $reel->slug),
                $channelName . ' uploaded a new reel!',
                'reel'
            );

            Log::info('[NOTIFY-PIPELINE][STAGE-1] NotifySubscribers job dispatched to queue successfully.');
        } catch (\Exception $e) {
            Log::error('[NOTIFY-PIPELINE][STAGE-1][ERROR] Could not dispatch reel notifications: ' . $e->getMessage());
        }
    }

    public function uploadVideoDirect(Request $request): array
    {
        $video = Video::where('id', $request->video_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$video || !$video->bunny_id) {
            return ['success' => false, 'message' => 'Video not found'];
        }

        Log::info('DirectUpload: Starting upload to Bunny', [
            'video_id' => $video->id,
            'bunny_id' => $video->bunny_id,
            'file_size' => $request->file('video')->getSize(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $file = $request->file('video');
            $fp = @fopen($file->getRealPath(), 'rb');

            if ($fp === false) {
                return ['success' => false, 'message' => 'Local source file could not be read.'];
            }

            $this->bunny->useVideoLibrary();
            $apiKey = gs('bunny_api_key') ?: config('bunny.api_key');
            $libraryId = gs('bunny_video_library_id') ?: config('bunny.library_id');

            $response = Http::withHeaders([
                'AccessKey' => $apiKey,
                'Content-Type' => 'application/octet-stream',
            ])
            ->timeout(3600)
            ->withBody($fp, 'application/octet-stream')
            ->put("https://video.bunnycdn.com/library/{$libraryId}/videos/{$video->bunny_id}");

            if ($response->successful()) {
                Log::info('DirectUpload: Upload successful', [
                    'video_id' => $video->id,
                    'bunny_id' => $video->bunny_id,
                    'status' => $response->status(),
                ]);
                return ['success' => true, 'message' => 'Video uploaded successfully'];
            }

            Log::error('DirectUpload: Bunny rejected upload', [
                'video_id' => $video->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'message' => 'Upload failed: ' . $response->body()];
        } catch (\Exception $e) {
            Log::error('DirectUpload: Exception during upload', [
                'video_id' => $video->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Upload error: ' . $e->getMessage()];
        }
    }

    public function uploadReelDirect(Request $request): array
    {
        $reel = Reel::where('id', $request->reel_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$reel) {
            return ['success' => false, 'message' => 'Reel not found'];
        }

        $isMixingReel = ($reel->is_duet || ($reel->music_source && $reel->music_source !== 'none'));

        if (empty($reel->bunny_id) || $isMixingReel) {
            Log::info('[REEL-TEMP] Starting cPanel local upload for reel', [
                'reel_id' => $reel->id,
                'file_size' => $request->file('video')->getSize(),
            ]);

            try {
                $tempDir = storage_path("app/reels/temp/{$reel->id}");
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }
                
                $file = $request->file('video');
                $file->move($tempDir, 'source.mp4');
                $videoPath = "reels/temp/{$reel->id}/source.mp4";

                $reel->update([
                    'video_path' => $videoPath,
                    'bunny_status' => null, // NULL during cPanel upload
                    'compression_status' => 0, // queued
                ]);

                Log::info('[REEL-TEMP] Source saved', [
                    'reel_id' => $reel->id,
                    'path' => $videoPath,
                ]);

                Log::info('[REEL-QUEUE] CompressReel dispatched', [
                    'reel_id' => $reel->id,
                ]);

                \App\Jobs\CompressReel::dispatch($reel);

                return ['success' => true, 'message' => 'Reel uploaded successfully and queued for processing.'];
            } catch (\Exception $e) {
                Log::error('[REEL-TEMP] cPanel upload failed', [
                    'reel_id' => $reel->id,
                    'error' => $e->getMessage(),
                ]);
                return ['success' => false, 'message' => 'Upload error: ' . $e->getMessage()];
            }
        }

        Log::info('DirectUpload: Starting legacy reel upload to Bunny', [
            'reel_id' => $reel->id,
            'bunny_id' => $reel->bunny_id,
            'file_size' => $request->file('video')->getSize(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            $file = $request->file('video');
            $videoPath = fileUploader($file, getFilePath('reel'));
            $reel->update(['video_path' => $videoPath]);

            $fullLocalPath = public_path(getFilePath('reel') . '/' . $videoPath);

            Log::info('[REEL-UPLOAD] completed, uploading local source to Bunny (streamed)', [
                'reel_id' => $reel->id,
                'bunny_id' => $reel->bunny_id,
                'bytes' => filesize($fullLocalPath),
            ]);

            $this->bunny->useReelLibrary();
            $apiKey = gs('bunny_api_key') ?: config('bunny.api_key');
            $libraryId = gs('bunny_reel_library_id') ?: config('bunny.library_id');

            $fp = @fopen($fullLocalPath, 'rb');
            if ($fp === false) {
                Log::error('DirectUpload: Reel source file not readable', [
                    'reel_id' => $reel->id,
                    'path' => $fullLocalPath,
                ]);
                return ['success' => false, 'message' => 'Local source file could not be read.'];
            }

            try {
                Log::info('[REEL-BUNNY] upload started', ['reel_id' => $reel->id, 'bunny_id' => $reel->bunny_id]);
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'AccessKey' => $apiKey,
                ])
                ->withBody($fp, 'application/octet-stream')
                ->timeout(3600)
                ->put("https://video.bunnycdn.com/library/{$libraryId}/videos/{$reel->bunny_id}");
            } finally {
                @fclose($fp);
            }

            Log::info('[REEL-BUNNY] upload finished', [
                'reel_id' => $reel->id,
                'bunny_id' => $reel->bunny_id,
                'status' => $response->status(),
            ]);

            if ($response->successful()) {
                $reel->update(['bunny_status' => 'processing']);

                Log::info('[REEL-BUNNY] upload completed; awaiting READY webhook to finalize normal reel', [
                    'reel_id' => $reel->id,
                    'bunny_id' => $reel->bunny_id,
                ]);

                return ['success' => true, 'message' => 'Reel uploaded successfully and will be processed once ready.'];
            }

            Log::error('DirectUpload: Bunny rejected reel upload', [
                'reel_id' => $reel->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'message' => 'Upload failed: ' . $response->body()];
        } catch (\Exception $e) {
            Log::error('DirectUpload: Reel exception', [
                'reel_id' => $reel->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => 'Upload error: ' . $e->getMessage()];
        }
    }
}
