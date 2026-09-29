<?php

namespace App\Services\Frontend;

use App\Constants\Status;
use App\Events\UserNotification;
use App\Jobs\ProcessVideo;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\Ad;
use App\Models\AdminNotification;
use App\Models\BannerAd;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\Like;
use App\Models\NotInterestedVideo;
use App\Models\Playlist;
use App\Models\PurchasedVideo;
use App\Models\Reel;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoCommission;
use App\Models\VideoEarning;
use App\Models\ViewLog;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoService
{
    public function show(Request $request, $video, $playlist = null): array
    {
        $query = Video::with(['user.channel', 'categories', 'copyrightStrikes' => function ($q) {
            $q->where('status', 'active');
        }]);

        if (is_numeric($video)) {
            $foundVideo = (clone $query)->where('slug', (string)$video)->first();
            $video = $foundVideo ?: $query->where('id', $video)->first();
        } else {
            $video = $query->where('slug', $video)->first();
        }

        if (!$video || $video->status == Status::DRAFT) {
            $reelSlug = $request->route('video') ?? (is_string($video) ? $video : null);
            if ($reelSlug) {
                $reel = \App\Models\Reel::where('slug', $reelSlug)->orWhere('id', $reelSlug)->first();
                if ($reel) {
                    return ['redirect' => route('reels.show', ['reel' => $reel->slug])];
                }
            }
            return ['redirect' => route('home'), 'notify' => ['error', 'This video is a draft and not available for viewing.']];
        }

        if ($video->visibility == Status::PRIVATE) {
            if (!auth()->check() || auth()->id() != $video->user_id) {
                abort(403, 'This video is private.');
            }
        }

        if ($video->isBunnyVideo() && $video->bunny_status !== 'ready') {
            abort(404, 'This video is currently processing and will be available soon.');
        }

        if ($video->copyrightStrikes->isNotEmpty() ||
            ($video->user && $video->user->copyrightStrikes()->where('status', 'active')->count() >= 3)) {
            abort(403, 'This content is unavailable due to a copyright strike.');
        }

        $hasAccess = true;
        if ($video->is_premium) {
            $hasAccess = false;
            if (auth()->check()) {
                if (auth()->id() == $video->user_id || auth()->user()->isPurchased($video->id)) {
                    $hasAccess = true;
                } elseif ($video->pricing_tier == 2) {
                    $hasAccess = auth()->user()->hasOttAccess();
                } elseif (auth()->user()->hasPremiumAccess()) {
                    $hasAccess = true;
                }
            }
        }

        $isScheduled = $video->scheduled_at && $video->scheduled_at->isFuture();
        if ($isScheduled && (!auth()->check() || auth()->id() != $video->user_id)) {
            $hasAccess = false;
        }

        $isAgeVerified = !$video->is_age_restricted || auth()->check();

        $videoPlayUrl = '';
        if ($video->isBunnyVideo() && $video->bunny_status === 'ready') {
            $videoPlayUrl = $video->getPlayUrl();
        } elseif ($video->hls_path) {
            $videoPlayUrl = asset($video->hls_path);
        }

        $playlistData = null;
        $playlistId = $request->list;
        if ($playlistId) {
            if ($playlistId === 'watch-later' && auth()->check()) {
                $playlistData = auth()->user()->playlists()->where('name', 'Watch Later')->with(['videos.user.channel', 'reels.user.channel'])->first();
                if (!$playlistData) {
                    $playlistData = (object)[
                        'id' => 'watch-later', 'name' => 'Watch Later',
                        'user' => auth()->user(), 'videos' => collect(), 'reels' => collect(),
                    ];
                }
            } elseif ($playlistId === 'liked' && auth()->check()) {
                $playlistData = (object)[
                    'id' => 'liked', 'name' => 'Liked Videos',
                    'user' => auth()->user(),
                    'videos' => auth()->user()->likedVideos()->with('user.channel')->get(),
                    'reels' => collect(),
                ];
            } else {
                $playlistData = Playlist::with(['videos.user.channel', 'reels.user.channel'])->find($playlistId);
            }
        }

        $commentSort = request()->get('comment_sort', 'new');
        $commentsQuery = $video->comments()->whereNull('parent_id')->orderBy('is_pinned', 'desc')->with(['user', 'replies.user', 'likes']);
        if ($commentSort === 'top') {
            $commentsQuery->withCount('likes')->orderBy('likes_count', 'desc');
        } else {
            $commentsQuery->latest();
        }
        $video->setRelation('comments', $commentsQuery->take(20)->get());

        $categoryIds = $video->categories->pluck('id')->toArray();
        $filter = $request->query('filter', 'all');

        $relatedQuery = Video::published()
            ->where('videos.id', '!=', $video->id)
            ->with('user.channel');

        if ($filter === 'channel') {
            $relatedQuery->where('user_id', $video->user_id);
        } elseif ($filter === 'related' && !empty($categoryIds)) {
            $relatedQuery->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $relatedVideos = $relatedQuery
            ->orderBy('views_count', 'desc')
            ->take(10)
            ->get();

        if ($relatedVideos->count() < 6 && $filter !== 'channel') {
            $existingIds = $relatedVideos->pluck('id')->push($video->id)->toArray();
            $extra = Video::published()
                ->whereNotIn('id', $existingIds)
                ->with('user.channel')
                ->latest()
                ->take(10 - $relatedVideos->count())
                ->get();
            $relatedVideos = $relatedVideos->merge($extra);
        }

        $userPlaylists = auth()->check() ? auth()->user()->playlists : collect();
        $ad = \App\Support\SafeCache::remember('random_active_ad', 300, function () {
            return Ad::where('is_active', true)->inRandomOrder()->first();
        });
        $reels = Reel::forUser()->latest()->take(10)->get();
        $general = gs();

        $now = now();
        $allBanners = \App\Support\SafeCache::remember('video_page_banners', 300, function () use ($now) {
            return BannerAd::whereIn('slot', ['slot1', 'slot2', 'slot3', 'slot4'])
                ->where('status', 1)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->get()
                ->groupBy('slot');
        });
        $slot1Banners = $allBanners->get('slot1', collect());
        $slot2Banners = $allBanners->get('slot2', collect());
        $slot3Banners = $allBanners->get('slot3', collect());
        $slot4Banners = $allBanners->get('slot4', collect());

        $userAgent = $request->userAgent();
        $device = 'Desktop';
        if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            $device = 'Tablet';
        } elseif (preg_match('/mobile|iphone|ipod|android.*mobile|blackberry|windows phone/i', $userAgent)) {
            $device = 'Mobile';
        }

        // Single source view logic: count only on actual play via AnalyticsService::updateWatchProgress
        // Page view (show) no longer increments views_count - prevents app 5 vs Bunny 0 desync.
        // We still log ViewLog for history/audience without inflating views_count.
        if (auth()->check()) {
            $videoId = $video->id;
            $userId = auth()->id();
            app()->terminating(function () use ($videoId, $userId, $device) {
                \App\Jobs\RecordVideoView::dispatch($userId, $videoId, $device);
            });
        }

        $pageTitle = trim($video->title);
        if (empty($pageTitle) || preg_match('/^Video - \d+$/i', $pageTitle) || preg_match('/^video-\d+$/i', $pageTitle)) {
            $pageTitle = optional(optional($video->user)->channel)->name ?? optional($video->user)->name ?? 'Video';
        }

        return compact(
            'video', 'relatedVideos', 'userPlaylists', 'ad', 'reels',
            'hasAccess', 'isAgeVerified', 'general', 'playlistData',
            'slot1Banners', 'slot2Banners', 'slot3Banners', 'slot4Banners',
            'videoPlayUrl', 'pageTitle'
        );
    }

    public function related(Request $request, $id): array
    {
        $video = Video::withoutGlobalScopes()->find($id);
        if (!$video) {
            return ['html' => ''];
        }

        $categoryIds = $video->categories->pluck('id')->toArray();
        $filter = $request->query('filter', 'all');

        $relatedQuery = Video::published()
            ->where('videos.id', '!=', $video->id)
            ->with('user.channel');

        if ($filter === 'channel') {
            $relatedQuery->where('user_id', $video->user_id);
        } elseif ($filter === 'related' && !empty($categoryIds)) {
            $relatedQuery->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            });
        }

        $relatedVideos = $relatedQuery->orderBy('views_count', 'desc')->paginate(10);

        return compact('relatedVideos');
    }

    public function download(Video $video): array
    {
        $hasAccess = true;
        if ($video->is_premium) {
            $hasAccess = false;
            if (auth()->check()) {
                if (auth()->id() == $video->user_id || auth()->user()->isPurchased($video->id) || auth()->user()->hasPremiumAccess()) {
                    $hasAccess = true;
                }
            }
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized access.');
        }

        $url = $video->getVideoUrl();
        $fileName = Str::slug($video->title) . '.mp4';

        if ($video->isBunnyVideo()) {
            return ['type' => 'bunny_stream', 'url' => $url, 'fileName' => $fileName];
        }

        $path = getFilePath('video') . '/' . $video->video_path;
        if (file_exists(public_path($path))) {
            return ['type' => 'local', 'path' => $path, 'fileName' => $fileName];
        }

        return ['type' => 'redirect', 'url' => $url];
    }

    public function recordAdImpression($video): array
    {
        if (is_numeric($video)) {
            $video = Video::where('slug', (string)$video)->first() ?: Video::find($video);
        } else {
            $video = Video::where('slug', $video)->first();
        }

        if (!$video) {
            return ['status' => 'error', 'message' => 'Video not found'];
        }

        $earning = VideoEarning::firstOrCreate(
            ['video_id' => $video->id, 'date' => now()->toDateString()],
            ['ad_impressions' => 0, 'estimated_revenue' => 0]
        );

        $earning->increment('ad_impressions');
        $earning->increment('estimated_revenue', gs('per_impression_earn') ?? 0.005);

        return ['status' => 'success'];
    }

    public function history(): array
    {
        $user = auth()->user();

        $logs = $user->viewLogs()
            ->with(['video' => function ($q) {
                $q->forUser()->with('user.channel');
            }, 'reel' => function ($q) {
                $q->forUser()->with('user.channel');
            }])
            ->latest('updated_at')
            ->take(60)
            ->get();

        $watchedVideos = $logs->whereNotNull('video_id')->map(fn($log) => $log->video)->filter()->unique('id')->take(20);
        $watchedReels = $logs->whereNotNull('reel_id')->map(fn($log) => $log->reel)->filter()->unique('id')->take(20);

        // Count/display only viewable videos so /history playlist cards stay in
        // sync with /watch-later (which uses Video::forUser()). A raw
        // withCount('videos') would keep counting private/unpublished/
        // struck/hidden videos after removal from the visible list.
        $playlists = $user->playlists()
            ->withCount(['videos as videos_count' => fn($q) => $q->forUser()])
            ->withCount(['reels as reels_count' => fn($q) => $q->forUser()])
            ->with(['videos' => fn($q) => $q->forUser(), 'reels' => fn($q) => $q->forUser()])
            ->latest()
            ->get();

        $likedVideos = $user->likedVideos()
            ->forUser()
            ->with('user.channel')
            ->latest()
            ->take(20)
            ->get();

        $commentedVideos = Video::forUser()
            ->whereHas('comments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with('user.channel')
            ->latest()
            ->take(20)
            ->get();

        return [
            'videos' => $watchedVideos,
            'reels' => $watchedReels,
            'playlists' => $playlists,
            'likedVideos' => $likedVideos,
            'commentedVideos' => $commentedVideos,
            'title' => 'You',
        ];
    }

    public function removeHistory($id, $isAjax = false): array
    {
        auth()->user()->viewLogs()->where('video_id', $id)->delete();

        if ($isAjax) {
            return ['status' => 'success', 'message' => 'Removed from watch history'];
        }

        return ['notify' => ['success', 'Removed from watch history']];
    }

    public function removeReelHistory($id, $isAjax = false): array
    {
        auth()->user()->viewLogs()->where('reel_id', $id)->delete();

        if ($isAjax) {
            return ['status' => 'success', 'message' => 'Removed from watch history'];
        }

        return ['notify' => ['success', 'Removed from watch history']];
    }

    public function clearHistory(): array
    {
        auth()->user()->viewLogs()->delete();
        return ['notify' => ['success', 'Watch history cleared']];
    }

    public function liked(): array
    {
        $user = auth()->user();

        $likedVideos = $user->likedVideos()->forUser()->with('user.channel')->latest('likes.created_at')->get()->map(function ($v) {
            $v->type = 'video';
            return $v;
        });

        $likedReels = $user->likedReels()->forUser()->with('user.channel')->latest('reel_likes.created_at')->get()->map(function ($r) {
            $r->type = 'reel';
            return $r;
        });

        $items = $likedVideos->merge($likedReels)->sortByDesc('created_at');

        return ['videos' => $items, 'title' => 'Liked videos'];
    }

    public function watchLater(): array
    {
        $user = auth()->user();
        $playlistIds = $user->playlists()
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Watch Later%')
                  ->orWhere('name', 'LIKE', '%watch later%');
            })
            ->pluck('id');

        if ($playlistIds->isEmpty()) {
            return ['videos' => collect(), 'title' => 'Watch Later'];
        }

        $videos = Video::forUser()
            ->whereHas('playlists', function($q) use ($playlistIds) {
                $q->whereIn('playlists.id', $playlistIds);
            })
            ->with('user.channel')
            ->latest()
            ->get()
            ->map(function ($v) {
                $v->type = 'video';
                return $v;
            });

        $reels = Reel::forUser()
            ->whereHas('playlists', function($q) use ($playlistIds) {
                $q->whereIn('playlists.id', $playlistIds);
            })
            ->with('user.channel')
            ->latest()
            ->get()
            ->map(function ($r) {
                $r->type = 'reel';
                return $r;
            });

        $items = $videos->merge($reels)->sortByDesc('created_at')->values();

        return ['videos' => $items, 'title' => 'Watch Later'];
    }

    public function create(Request $request = null): array
    {
        if (!auth()->user()->channel) {
            return ['redirect' => route('channels.create')];
        }

        $draft = null;
        if ($request && $request->has('draft_id')) {
            $draft = Video::where('id', $request->draft_id)
                          ->where('user_id', auth()->id())
                          ->where('status', \App\Constants\Status::DRAFT)
                          ->first();
        }

        $categories = Category::all();
        $hasPremiumAccess = auth()->user()->hasPremiumAccess();
        $general = GeneralSetting::first();

        return compact('categories', 'hasPremiumAccess', 'general', 'draft');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->channel) {
            return ['redirect' => route('channels.create')];
        }

        $videoFile = $request->file('video');
        $videoPath = fileUploader($videoFile, getFilePath('video'));

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = fileUploader($request->file('thumbnail'), getFilePath('thumbnail'));
        }

        $baseSlug = Str::slug($request->title ?: 'video');
        $slug = $baseSlug;
        while (Video::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . Str::random(5);
        }

        $video = new Video;
        $video->user_id = auth()->id();
        $video->title = $request->title;
        $video->slug = $slug;
        $video->description = $request->description;
        $video->video_path = $videoPath;
        $video->thumbnail_path = $thumbnailPath;

        $tierMap = ['free' => 0, 'premium' => 1, 'exclusive' => 2];
        $pricingTierStr = $request->pricing_tier ?? 'free';
        $pricingTierInt = $tierMap[$pricingTierStr] ?? 0;

        if (in_array($pricingTierStr, ['premium', 'exclusive']) && auth()->user()->hasPremiumAccess()) {
            $video->pricing_tier = $pricingTierInt;
            $video->price = $request->price ?? 0;
            $video->is_premium = 1;
        } else {
            $video->pricing_tier = 0;
            $video->price = 0;
            $video->is_premium = 0;
        }

        $video->status = 'ready';
        $video->visibility = $request->visibility ?? Status::PUBLIC;
        $video->location = $request->location;

        if ($request->schedule_video == '1' && $request->schedule_date && $request->schedule_time) {
            $video->scheduled_at = \Carbon\Carbon::parse($request->schedule_date . ' ' . $request->schedule_time, 'Asia/Kolkata')->setTimezone(config('app.timezone'));
        }
        $video->is_age_restricted = $request->has('is_age_restricted');
        $video->duration = $request->duration;

        if ($request->hasFile('captions')) {
            $video->captions_path = fileUploader($request->file('captions'), getFilePath('subtitle'));
        }

        $video->save();
        $video->categories()->attach($request->category_id);

        AdminNotification::create([
            'user_id' => auth()->id(),
            'title' => 'New video uploaded: ' . $video->title,
            'click_url' => route('admin.videos.index'),
        ]);

        // Notifications
        try {
            $user = auth()->user();
            if ($user->channel) {
                $channelName = $user->channel->name ?? $user->username;
                \App\Jobs\NotifySubscribers::dispatch(
                    $user->id,
                    $channelName,
                    $channelName . ' uploaded a new video: ' . $video->title,
                    route('videos.show', $video->slug),
                    'New Video Uploaded!'
                );
            }
        } catch (\Exception $e) {
            Log::error('Subscriber Notification Failed: ' . $e->getMessage());
        }

        try {
            ProcessVideo::dispatch($video);
        } catch (\Exception $e) {
            Log::error('Video processing failed: ' . $e->getMessage());
        }

        return array_merge($video->toArray(), ['notify' => ['success', 'Video uploaded successfully and is now available for playback!'], 'redirect' => route('videos.show', $video->slug), 'video' => $video]);
    }

    public function toggleLike($video): array
    {
        if (is_numeric($video)) {
            $video = Video::where('slug', (string)$video)->first() ?: Video::find($video);
        } elseif (is_string($video)) {
            $video = Video::where('slug', $video)->first();
        }

        if (!$video) {
            return ['status' => 'error', 'message' => 'Video not found'];
        }

        session_write_close(); // Release session lock early for instant response

        $like = Like::where('user_id', auth()->id())
            ->where('video_id', $video->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'video_id' => $video->id,
            ]);
            $liked = true;

            if ($video->user_id !== auth()->id()) {
                try {
                    $likerName = auth()->user()->name;
                    dispatch(function () use ($video, $likerName) {
                        try {
                            broadcast(new \App\Events\UserNotification(
                                $video->user_id,
                                $likerName . ' liked your video: ' . $video->title,
                                route('videos.show', $video->slug)
                            ));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Toggle Like broadcast failed: ' . $e->getMessage());
                        }
                    })->afterResponse();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Toggle Like dispatch failed: ' . $e->getMessage());
                }
            }
        }

        return [
            'liked' => $liked,
            'likes_count' => $video->likes()->count(),
        ];
    }

    /**
     * Idempotent remove-from-liked (delete-only, never creates).
     * Safe under rapid repeat clicks: N calls always end at "removed".
     */
    public function removeLike($video): array
    {
        if (is_numeric($video)) {
            $video = Video::where('slug', (string)$video)->first() ?: Video::find($video);
        } elseif (is_string($video)) {
            $video = Video::where('slug', $video)->first();
        }

        if (!$video) {
            return ['status' => 'error', 'message' => 'Video not found'];
        }

        session_write_close(); // Release session lock early for instant response

        Like::where('user_id', auth()->id())
            ->where('video_id', $video->id)
            ->delete();

        return [
            'removed' => true,
            'likes_count' => $video->likes()->count(),
        ];
    }

    public function createVideoOrder(Request $request, Video $video): array
    {
        if (!auth()->check()) {
            return ['error' => 'Authentication required'];
        }

        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');

        if (!$apiKey || !$apiSecret) {
            Log::error('Razorpay configuration missing for Video PPV purchase.');
            return ['error' => 'Payment gateway not configured. Please contact admin.'];
        }

        try {
            $api = new \Razorpay\Api\Api($apiKey, $apiSecret);
            $trx = getTrx();

            $order = $api->order->create([
                'receipt'         => $trx,
                'amount'          => round($video->price * 100),
                'currency'        => 'INR',
                'payment_capture' => '1',
            ]);

            // Store order info in session for verification
            session()->put('video_ppv_' . $trx, [
                'order_id' => $order->id,
                'video_id' => $video->id,
                'amount'   => $video->price,
            ]);

            return [
                'success'   => true,
                'order_id'  => $order->id,
                'amount'    => $order->amount,
                'currency'  => $order->currency,
                'key'       => $apiKey,
                'name'      => auth()->user()->username,
                'email'     => auth()->user()->email,
                'trx'       => $trx,
                'plan_name' => $video->title,
            ];
        } catch (\Exception $e) {
            Log::error('Video PPV Razorpay Order Failed: ' . $e->getMessage(), [
                'user_id'  => auth()->id(),
                'video_id' => $video->id,
            ]);
            return ['error' => 'Payment initialization failed: ' . $e->getMessage()];
        }
    }

    public function purchase(Request $request, Video $video): array
    {
        if (!auth()->check()) {
            return ['error' => 'Authentication required'];
        }

        $request->validate([
            'razorpay_payment_id' => 'required',
            'razorpay_order_id'   => 'required',
            'razorpay_signature'  => 'required',
            'trx'                 => 'required',
        ]);

        $apiKey = config('services.razorpay.key');
        $apiSecret = config('services.razorpay.secret');

        try {
            $api = new \Razorpay\Api\Api($apiKey, $apiSecret);

            // Verify payment signature
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);

            // Payment is auto-captured, verify status
            $payment = $api->payment->fetch($request->razorpay_payment_id);
            Log::info('Video PPV Payment Status: ' . $payment->status, [
                'video_id' => $video->id,
                'trx'      => $request->trx,
                'amount'   => $payment->amount,
            ]);

            if ($payment->status !== 'captured') {
                return ['error' => 'Payment was not captured. Please try again.'];
            }
        } catch (\Exception $e) {
            Log::error('Video PPV Payment Verification Failed: ' . $e->getMessage(), [
                'video_id' => $video->id,
                'trx'      => $request->trx,
            ]);
            return ['error' => 'Payment verification failed: ' . $e->getMessage()];
        }

        // Clear session
        session()->forget('video_ppv_' . $request->trx);

        $purchase = new PurchasedVideo;
        $purchase->user_id = auth()->id();
        $purchase->video_id = $video->id;
        $purchase->owner_id = $video->user_id;
        $purchase->price = $video->price;
        $purchase->trx = $request->razorpay_payment_id;
        $purchase->save();

        $totalAmount = $video->price;
        $creatorPercent = gs('ppv_creator_commission_percent') ?? 60;
        $creatorComm = ($totalAmount * $creatorPercent) / 100;
        $adminComm = $totalAmount - $creatorComm;

        VideoCommission::create([
            'video_id' => $video->id,
            'purchaser_id' => auth()->id(),
            'creator_id' => $video->user_id,
            'total_amount' => $totalAmount,
            'admin_commission' => $adminComm,
            'creator_commission' => $creatorComm,
            'trx' => $request->razorpay_payment_id ?? getTrx(),
        ]);

        $creator = User::find($video->user_id);
        if ($creator) {
            $creator->balance += $creatorComm;
            $creator->save();

            Transaction::create([
                'user_id' => $creator->id,
                'video_id' => $video->id,
                'amount' => $creatorComm,
                'post_balance' => $creator->balance,
                'charge' => $adminComm,
                'trx_type' => '+',
                'details' => 'Commission from video PPV purchase: ' . $video->title,
                'trx' => $request->razorpay_payment_id ?? getTrx(),
                'remark' => 'video_ppv_commission',
            ]);
        }

        try {
            $creator = $video->user;
            if ($creator) {
                SendFirebaseNotificationJob::dispatch($creator->id, [
                    'title' => 'Video Purchased!',
                    'body' => auth()->user()->username . ' just purchased your video: ' . $video->title,
                    'url' => route('studio.videos'),
                ], 'user');
            }
        } catch (\Exception $e) {
            Log::error('Creator Purchase Notification Dispatch Failed: ' . $e->getMessage());
        }

        return ['success' => 'Video purchased successfully!'];
    }


    public function trending(): array
    {
        $page = request()->get('page', 1);
        $userId = auth()->id() ?? 'guest';
        $cacheKey = "trending_videos_v3_page_{$page}_user_{$userId}";
        
        $videos = \App\Support\SafeCache::remember($cacheKey, 300, function () {
            return Video::forUser()->with(['user.channel'])
                ->withCount(['viewLogs as daily_views' => function ($q) {
                    $q->where('created_at', '>=', now()->subDay());
                }])
                ->orderBy('is_trending', 'desc')
                ->orderBy('daily_views', 'desc')
                ->orderBy('views_count', 'desc')
                ->orderBy('id', 'desc')
                ->paginate(24);
        });

        if (auth()->check()) {
            $videos->getCollection()->load(['authLikes', 'authPlaylists']);
        }

        return compact('videos');
    }

    public function notInterested(Video $video): array
    {
        $user = auth()->user();
        if (!$user) {
            return ['error' => 'Login required'];
        }
        if ((string)$video->user_id === (string)$user->id) {
            return ['error' => 'You cannot hide your own video'];
        }

        NotInterestedVideo::firstOrCreate([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        $userId = $user->id;
        \Illuminate\Support\Facades\Cache::forget("home_trending_videos_v3_user_{$userId}");
        \Illuminate\Support\Facades\Cache::forget("home_featured_videos_v3_user_{$userId}");
        
        // Clear caches for page 1 to 5 across trending and home
        for ($i = 1; $i <= 5; $i++) {
            \Illuminate\Support\Facades\Cache::forget("trending_videos_v3_page_{$i}_user_{$userId}");
            \Illuminate\Support\Facades\Cache::forget("home_videos_v3_cat__page_{$i}_user_{$userId}");
        }

        // Also clear caches for any active category
        $categories = \Illuminate\Support\Facades\Cache::remember('active_categories_slugs', 3600, function() {
            return \App\Models\Category::active()->pluck('slug')->toArray();
        });

        foreach ($categories as $slug) {
            for ($i = 1; $i <= 3; $i++) {
                \Illuminate\Support\Facades\Cache::forget("home_videos_v3_cat_{$slug}_page_{$i}_user_{$userId}");
            }
        }

        return [
            'status' => 'success',
            'message' => 'Video hidden from your feed',
        ];
    }

    public function report(Request $request, Video $video): array
    {
        if ((string)$video->user_id === (string)auth()->id()) {
            return ['success' => false, 'message' => 'You cannot report your own video'];
        }
        Report::create([
            'user_id' => auth()->id(),
            'video_id' => $video->id,
            'reported_user_id' => $video->user_id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        AdminNotification::create([
            'user_id' => auth()->id(),
            'title' => 'New video report: ' . $video->title,
            'click_url' => route('admin.reports.index'),
        ]);

        return [
            'success' => true,
            'message' => 'Report submitted successfully',
        ];
    }

    public function recordImpression($video): array
    {
        if (is_numeric($video)) {
            $video = Video::where('slug', (string)$video)->first() ?: Video::find($video);
        } else {
            $video = Video::where('slug', $video)->first();
        }

        if (!$video) {
            return ['status' => 'error', 'message' => 'Video not found'];
        }

        $video->increment('impressions');

        try {
            \App\Models\VideoDailyStat::firstOrCreate(
                ['video_id' => $video->id, 'date' => \Carbon\Carbon::today()->toDateString()],
                ['impressions' => 0, 'views' => 0]
            )->increment('impressions');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to log daily impression: ' . $e->getMessage());
        }

        return ['success' => true];
    }
}
