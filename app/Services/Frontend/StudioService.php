<?php

namespace App\Services\Frontend;

use App\Models\Video;
use App\Models\Reel;
use App\Models\ReelHashtag;
use App\Models\ReelMention;
use App\Models\ReelTag;
use App\Models\Category;
use App\Models\User;
use App\Models\Subscription;
use App\Models\ViewLog;
use App\Models\VideoWatchLog;
use App\Models\VideoEarning;
use App\Models\ReelView;
use App\Models\Like;
use App\Models\ReelLike;
use App\Models\Comment;
use App\Models\ReelComment;
use App\Models\Report;
use App\Models\ReelReport;
use App\Models\SavedAudio;
use App\Models\ReelMusic;
use App\Models\Membership;
use App\Models\FirebaseToken;
use App\Models\GeneralSetting;
use App\Models\Plan;
use App\Services\BunnyStreamService;
use App\Constants\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudioService
{
    public function __construct(
        protected UploadService $uploadService
    ) {}

    public function dashboard(): array
    {
        $user = auth()->user();
        $videos = $user->videos()->withCount(['likes', 'comments'])->get();
        $reels = Reel::where('user_id', $user->id)->get();

        $totalViews = $videos->sum('views_count') + $reels->sum('views_count');
        $totalLikes = $videos->sum('likes_count') + $reels->sum('likes_count');
        $totalComments = $videos->sum('comments_count') + $reels->sum('comments_count');
        $subscribersCount = $user->channel?->subscribers_count ?? 0;

        $thirtyDaysAgo = now()->subDays(30);
        $channelId = $user->channel?->id;

        $newSubscribers = $channelId ? Subscription::where('channel_id', $channelId)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count() : 0;
        $subscriberGrowth = $subscribersCount > 0 ? ($newSubscribers / $subscribersCount) * 100 : null;
        $subscriberGrowthText = $subscriberGrowth !== null ? '+' . round($subscriberGrowth, 1) . '%' : 'N/A';

        $newViews = ViewLog::where(function ($q) use ($user) {
            $q->whereIn('video_id', $user->videos()->pluck('id'))
                ->orWhereIn('reel_id', Reel::where('user_id', $user->id)->pluck('id'));
        })
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();
        $viewsGrowth = $totalViews > 0 ? ($newViews / $totalViews) * 100 : null;
        $viewsGrowthText = $viewsGrowth !== null ? '+' . round($viewsGrowth, 1) . '%' : 'N/A';

        $newLikes = Like::whereIn('video_id', $user->videos()->pluck('id'))
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count() +
            ReelLike::whereIn('reel_id', Reel::where('user_id', $user->id)->pluck('id'))
                ->where('is_like', 1)
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->count();
        $likesGrowth = $totalLikes > 0 ? ($newLikes / $totalLikes) * 100 : null;
        $likesGrowthText = $likesGrowth !== null ? '+' . round($likesGrowth, 1) . '%' : 'N/A';

        $newComments = Comment::whereIn('video_id', $user->videos()->pluck('id'))
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count() +
            ReelComment::whereIn('reel_id', Reel::where('user_id', $user->id)->pluck('id'))
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->count();
        $commentsGrowth = $totalComments > 0 ? ($newComments / $totalComments) * 100 : null;
        $commentsGrowthText = $commentsGrowth !== null ? '+' . round($commentsGrowth, 1) . '%' : 'N/A';

        $dashboardStats = [
            'subscribers' => $subscriberGrowthText,
            'views' => $viewsGrowthText,
            'likes' => $likesGrowthText,
            'comments' => $commentsGrowthText,
        ];

        $recentVideos = $user->videos()->latest()->take(5)->get();
        $recentReels = Reel::where('user_id', $user->id)->latest()->take(5)->get();
        $plans = Plan::latest()->get();
        $userPlanIds = $user->purchasedPlans()->where('expired_date', '>', now())->pluck('plan_id')->toArray();

        $videoReports = Report::where('reported_user_id', $user->id)
            ->where('status', 1)
            ->with('video')
            ->latest()
            ->get();

        $reelReports = ReelReport::whereHas('reel', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('status', 1)
            ->with('reel')
            ->latest()
            ->get();

        $moderationReports = $videoReports->concat($reelReports)->sortByDesc('created_at')->take(10);

        return compact('totalViews', 'totalLikes', 'totalComments', 'subscribersCount', 'recentVideos', 'recentReels', 'plans', 'userPlanIds', 'moderationReports', 'dashboardStats');
    }

    public function videos(Request $request): array
    {
        $status = $request->status;
        $videosQuery = auth()->user()->videos()->latest();
        $reelsQuery = Reel::where('user_id', auth()->id())->latest();

        if ($status === 'draft') {
            $videosQuery->draft();
            $reelsQuery->draft();
        } elseif ($status === 'published') {
            $videosQuery->published()->public();
            $reelsQuery->published()->public();
        } elseif ($status === 'private') {
            $videosQuery->private();
            $reelsQuery->private();
        }

        $videos = $videosQuery->withCount(['comments', 'likes'])->paginate(15, ['*'], 'videos_page');
        $reels = $reelsQuery->paginate(15, ['*'], 'reels_page');

        $playlistIds = auth()->user()->playlists()
            ->where(function($q) {
                $q->where('name', 'LIKE', '%Watch Later%')
                  ->orWhere('name', 'LIKE', '%watch later%');
            })
            ->pluck('id');

        // Match the public /watch-later visibility rules and the blade's
        // slug-less skip (@continue): only countable, viewable items here.
        $watchLaterVideos = Video::query()
            ->forUser()
            ->whereNotNull('videos.slug')
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

        $watchLaterReels = Reel::query()
            ->forUser()
            ->whereNotNull('reels.slug')
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

        $allWatchLaters = $watchLaterVideos->merge($watchLaterReels)->sortByDesc('created_at')->values();
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage('watchlater_page');
        $perPage = 15;
        $watchLaters = new \Illuminate\Pagination\LengthAwarePaginator(
            $allWatchLaters->forPage($page, $perPage),
            $allWatchLaters->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'pageName' => 'watchlater_page']
        );

        $playlists = auth()->user()->playlists()->where(function($q) {
                $q->where('name', 'NOT LIKE', '%Watch Later%')
                  ->where('name', 'NOT LIKE', '%watch later%');
            })->withCount('videos')->with(['videos' => fn($q) => $q->limit(1), 'reels' => fn($q) => $q->limit(1), 'user.channel'])->latest()->paginate(15, ['*'], 'playlists_page');

        return compact('videos', 'reels', 'watchLaters', 'playlists');
    }

    public function savedAudios(): array
    {
        $user = auth()->user();
        $savedAudios = SavedAudio::where('user_id', $user->id)
            ->with('reelMusic')
            ->latest()
            ->paginate(15);

        return compact('savedAudios');
    }

    public function duets(): array
    {
        $user = auth()->user();
        $duets = Reel::where('user_id', $user->id)
            ->where('is_duet', true)
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(15);

        return ['reels' => $duets, 'pageTitle' => 'Your Duets & Remixes'];
    }

    public function analytics(): array
    {
        return ['pageTitle' => 'Channel Analytics'];
    }

    public function edit(Video $video): array
    {
        $categories = Category::all();
        $hasPremiumAccess = auth()->user()->hasPremiumAccess();
        return compact('video', 'categories', 'hasPremiumAccess');
    }

    public function update(Request $request, Video $video): array
    {
        if (auth()->id() != $video->user_id)
            abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:20480',
            'language' => 'nullable|string|max:255',
            'price' => 'nullable|integer|min:0',
        ]);

        $visibilityVal = $request->visibility;
        $statusVal = $video->status;
        $wasDraft = in_array($statusVal, [Status::DRAFT, 'draft', 0, '0']);

        if ($visibilityVal === 'draft') {
            $statusVal = Status::DRAFT;
            $visibilityVal = Status::PRIVATE;
        } else if ($wasDraft && in_array($visibilityVal, ['0', '1', 0, 1, 'public', 'private'])) {
            $statusVal = Status::PUBLISHED;
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'language' => $request->language,
            'visibility' => ($visibilityVal === 'draft' ? Status::PRIVATE : ($visibilityVal ?? Status::PUBLIC)),
            'status' => $statusVal,
            'location' => $request->location,
            'is_age_restricted' => $request->has('is_age_restricted'),
            'duration' => $request->duration,
            'scheduled_at' => ($request->schedule_video == '1' && $request->schedule_date && $request->schedule_time)
                ? Carbon::parse($request->schedule_date . ' ' . $request->schedule_time, 'Asia/Kolkata')->setTimezone(config('app.timezone'))
                : null,
        ];

        $tierMap = ['free' => 0, 'premium' => 1, 'exclusive' => 2];
        $pricingTierStr = $request->pricing_tier ?? 'free';
        $pricingTierInt = $tierMap[$pricingTierStr] ?? 0;

        if (in_array($pricingTierStr, ['premium', 'exclusive']) && auth()->user()->hasPremiumAccess()) {
            $data['pricing_tier'] = $pricingTierInt;
            $data['price'] = (int) ($request->price ?? 0);
            $data['is_premium'] = 1;
        } else {
            $data['pricing_tier'] = 0;
            $data['price'] = 0;
            $data['is_premium'] = 0;
        }

        if ($request->hasFile('captions')) {
            $data['captions_path'] = fileUploader($request->file('captions'), getFilePath('subtitle'), null, $video->captions_path);
        }

        if ($video->title !== $request->title) {
            $slug = Str::slug($request->title);
            if (empty($slug)) {
                $slug = 'video-' . Str::random(6);
            }
            if (Video::where('slug', $slug)->where('id', '!=', $video->id)->exists()) {
                $slug = $slug . '-' . Str::random(5);
            }
            $data['slug'] = $slug;
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = fileUploader($request->file('thumbnail'), getFilePath('thumbnail'), null, $video->thumbnail_path);
        }

        $video->update($data);

        // If video was a draft and creator clicks Save Video (or changes visibility), attempt publishVideo
        if ($wasDraft && $visibilityVal !== 'draft') {
            $pubResult = $this->uploadService->publishVideo($video);
            if (!$pubResult['success']) {
                // Keep video in DRAFT status without aborting form update
                $video->update(['status' => Status::DRAFT]);
            }
        }

        if ($request->has('category_id')) {
            $video->categories()->sync([$request->category_id]);
        }

        if ($request->has('tags')) {
            $video->tags()->delete();
            $tags = is_array($request->tags) ? $request->tags : json_decode($request->tags, true);
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if (trim($tag)) {
                        $video->tags()->create(['tag' => trim($tag)]);
                    }
                }
            }
        } else {
            $video->tags()->delete();
        }

        if ($video->isBunnyVideo()) {
            try {
                app(BunnyStreamService::class)->updateVideo($video->bunny_id, ['title' => $request->title]);
            } catch (\Exception $e) {
                Log::warning('Failed to update video in Bunny Stream: ' . $e->getMessage());
            }
        }

        return ['success' => 'Video updated successfully!'];
    }

    public function destroy(Video $video): void
    {
        if (auth()->id() != $video->user_id)
            abort(403);

        $video->deleteWithAssets();
    }

    public function monetization(): array
    {
        $user = auth()->user();

        $general = GeneralSetting::first();
        $videoViews = $user->videos()->sum('views_count');
        $reelViews = Reel::where('user_id', $user->id)->sum('views_count');
        $totalViews = $videoViews + $reelViews;
        $totalSubscribers = $user->channel?->subscribers_count ?? 0;
        $videoWatchSeconds = $user->videos()->sum('total_watch_time');
        $reelDwellSeconds = ReelView::whereIn('reel_id', $user->reels()->pluck('id'))->sum('dwell_seconds');
        $totalWatchHours = round(($videoWatchSeconds + $reelDwellSeconds) / 3600, 2);

        $totalEarnings = $user->videos()->with('earnings')->get()->flatMap->earnings->sum('estimated_revenue');
        $thisMonthEarnings = $user->videos()->with([
            'earnings' => function ($q) {
                $q->whereMonth('date', now()->month)->whereYear('date', now()->year);
            }
        ])->get()->flatMap->earnings->sum('estimated_revenue');

        $memberships = $user->channel ? $user->channel->memberships()->get() : collect();
        $monthlyMembershipRevenue = $memberships->sum(function ($tier) {
            return $tier->subscribers()->where('status', 'active')->count() * $tier->price;
        });

        return compact('totalEarnings', 'thisMonthEarnings', 'memberships', 'monthlyMembershipRevenue', 'general', 'totalViews', 'totalSubscribers', 'totalWatchHours');
    }

    public function storeMembership(Request $request)
    {
        $channel = auth()->user()->channel;
        if (!$channel)
            abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'perks' => 'required|string',
        ]);

        $perksArray = array_filter(array_map('trim', explode(',', $request->perks)));

        $channel->memberships()->create([
            'name' => $request->name,
            'price' => $request->price,
            'perks' => $perksArray,
        ]);
    }

    public function updateMembership(Request $request, Membership $membership): void
    {
        $user = auth()->user();
        $channel = $user->channel;
        if (!$channel || $membership->channel_id != $channel->id) {
            Log::warning('Membership update denied - ownership mismatch', [
                'user_id' => $user->id,
                'channel_id' => $channel?->id,
                'membership_id' => $membership->id,
                'membership_channel_id' => $membership->channel_id,
            ]);
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'perks' => 'required|string',
        ]);

        $oldData = [
            'name' => $membership->name,
            'price' => $membership->price,
            'perks' => $membership->perks,
        ];

        $perksArray = array_filter(array_map('trim', explode(',', $request->perks)));

        $membership->update([
            'name' => $request->name,
            'price' => $request->price,
            'perks' => $perksArray,
        ]);

        Log::info('Membership tier updated', [
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'membership_id' => $membership->id,
            'old' => $oldData,
            'new' => [
                'name' => $request->name,
                'price' => $request->price,
                'perks' => $perksArray,
            ],
        ]);
    }

    public function destroyMembership(Membership $membership): void
    {
        $user = auth()->user();
        $channel = $user->channel;
        if (!$channel || $membership->channel_id != $channel->id) {
            Log::warning('Membership delete denied - ownership mismatch', [
                'user_id' => $user->id,
                'channel_id' => $channel?->id,
                'membership_id' => $membership->id,
            ]);
            abort(403);
        }

        $tierData = [
            'id' => $membership->id,
            'name' => $membership->name,
            'price' => $membership->price,
            'perks' => $membership->perks,
            'channel_id' => $membership->channel_id,
        ];

        $membership->delete();
        Log::info('Membership tier deleted', [
            'user_id' => $user->id,
            'channel_id' => $channel->id,
            'deleted_membership' => $tierData,
        ]);
    }

    public function reels()
    {
        return ['redirect' => route('studio.videos', ['tab' => 'reels'])];
    }

    public function editReel(Reel $reel): array
    {
        $categories = Category::active()->get();
        $musicTracks = ReelMusic::active()->latest()->take(50)->get();
        return compact('reel', 'categories', 'musicTracks');
    }

    public function updateReel(Request $request, Reel $reel): array
    {
        if (auth()->id() != $reel->user_id)
            abort(403);

        Log::info('updateReel Request received:', [
            'reel_id' => $reel->id,
            'slug' => $reel->slug,
            'hashtags_param' => $request->input('hashtags'),
            'tags_param' => $request->input('tags'),
            'description' => $request->input('description')
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2200',
            'thumbnail' => 'nullable|image|max:20480',
            'visibility' => 'required|in:0,1',
            'language' => 'nullable|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'language' => $request->language,
            'category_id' => $request->category_id,
            'is_age_restricted' => $request->boolean('is_age_restricted'),
            'allow_comments' => $request->boolean('allow_comments', true),
            'allow_duet' => $request->boolean('allow_duet', true),
            'allow_stitch' => $request->boolean('allow_stitch', true),
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = fileUploader($request->file('thumbnail'), getFilePath('reelThumbnail'), getFileSize('reelThumbnail'), $reel->thumbnail_path);
        }

        $reel->update($data);

        $reel->hashtags()->delete();
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
        }

        $reel->mentions()->where('source', 'description')->delete();
        foreach (Reel::extractMentions($request->description) as $username) {
            $mentioned = User::where('username', $username)->first();
            if ($mentioned) {
                ReelMention::create(['reel_id' => $reel->id, 'mentioned_user_id' => $mentioned->id, 'source' => 'description']);
            }
        }

        $reel->tags()->delete();
        $reelTags = $request->tags ?? [];
        if (empty($reelTags) && isset($hashtags) && is_array($hashtags)) {
            $reelTags = $hashtags;
        }
        foreach ($reelTags as $tag) {
            ReelTag::create(['reel_id' => $reel->id, 'tag' => $tag]);
        }

        if ($reel->isBunnyReel()) {
            try {
                app(BunnyStreamService::class)->updateVideo($reel->bunny_id, ['title' => $request->title]);
            } catch (\Exception $e) {
                Log::warning('Failed to update reel in Bunny Stream: ' . $e->getMessage());
            }
        }

        return ['success' => 'Reel updated successfully!'];
    }

    public function destroyReel(Reel $reel): void
    {
        if (auth()->id() != $reel->user_id)
            abort(403);

        $reel->deleteWithAssets();
    }

    public function purchasedVideos(): array
    {
        $purchasedVideos = auth()->user()->purchasedVideos()->with('video.user')->latest()->paginate(15);
        return compact('purchasedVideos');
    }

    public function makePremium(Request $request, Video $video): array
    {
        if ($video->user_id != auth()->id())
            abort(403);

        if (!auth()->user()->hasPremiumAccess()) {
            return ['error' => 'You need a premium plan to upload or manage premium videos.'];
        }

        $request->validate(['price' => 'nullable|numeric|min:0']);

        $video->is_premium = 1;
        if ($request->price !== null) {
            $video->price = $request->price;
        }
        $video->save();

        return ['success' => 'Video is now premium!'];
    }

    public function toggleFeatured(Video $video): array
    {
        if ($video->user_id != auth()->id())
            abort(403);

        if (!auth()->user()->hasFeaturedAccess()) {
            return ['error' => 'You need a Creator plan with Featured Access enabled to feature videos. Please upgrade your plan.'];
        }

        $video->is_featured = !$video->is_featured;
        $video->save();

        $status = $video->is_featured ? 'featured' : 'unfeatured';
        return ['success' => "Video $status successfully"];
    }

    public function updateFcmToken(Request $request): array
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string|in:mobile,tablet,desktop',
        ]);

        $token = FirebaseToken::updateOrCreate(
            ['token' => $request->token],
            [
                'user_id' => auth()->id(),
                'device_type' => $request->device_type,
            ]
        );

        Log::info('[FCM TOKEN SAVED] Token generated & synced successfully', [
            'id' => $token->id,
            'user_id' => $token->user_id,
            'device_type' => $token->device_type,
            'fcm_token' => $token->token,
            'status' => $token->wasRecentlyCreated ? 'NEW TOKEN CREATED' : 'TOKEN UPDATED'
        ]);


        return ['success' => true];
    }

    public function deleteThumbnail(Video $video): array
    {
        if (auth()->id() != $video->user_id)
            abort(403);

        if ($video->thumbnail_path) {
            $thumbPath = public_path(getFilePath('thumbnail') . '/' . $video->thumbnail_path);
            if (file_exists($thumbPath))
                @unlink($thumbPath);
            $video->thumbnail_path = null;
            $video->save();
            return ['success' => 'Thumbnail deleted successfully'];
        }

        return ['error' => 'No thumbnail found to delete'];
    }

    public function deleteReelThumbnail(Reel $reel): array
    {
        if (auth()->id() != $reel->user_id)
            abort(403);

        if ($reel->thumbnail_path) {
            $tp = public_path(getFilePath('reelThumbnail') . '/' . $reel->thumbnail_path);
            if (file_exists($tp))
                @unlink($tp);
            $reel->thumbnail_path = null;
            $reel->save();
            return ['success' => 'Reel thumbnail deleted successfully'];
        }

        return ['error' => 'No thumbnail found to delete'];
    }

    public function overview(Request $request): array
    {
        $user = auth()->user();
        $days = $request->get('days', 28);
        $startDate = now()->subDays($days)->startOfDay();

        $videoIds = $user->videos()->pluck('id');
        $reelIds = $user->reels()->pluck('id');

        $totalWatchSeconds = $user->videos()->sum('total_watch_time')
            + ReelView::whereIn('reel_id', $reelIds)
                ->where('created_at', '>=', $startDate)
                ->sum('dwell_seconds');
        $totalWatchTimeHours = round($totalWatchSeconds / 3600, 2);

        $viewLogs = ViewLog::where(function($q) use ($videoIds, $reelIds) {
                $q->whereIn('video_id', $videoIds)
                  ->orWhereIn('reel_id', $reelIds);
            })
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function($log) {
                return Carbon::parse($log->created_at)->format('Y-m-d');
            });

        $videoWatchLogs = VideoWatchLog::whereIn('video_id', $videoIds)
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function($log) {
                return Carbon::parse($log->created_at)->format('Y-m-d');
            });

        $reelDwellByDay = ReelView::whereIn('reel_id', $reelIds)
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function($log) {
                return Carbon::parse($log->created_at)->format('Y-m-d');
            });
            
        $earningsLogs = VideoEarning::whereIn('video_id', $videoIds)
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function($log) {
                return Carbon::parse($log->created_at)->format('Y-m-d');
            });
            
        $channelId = $user->channel?->id;
        $subsLog = collect();
        if ($channelId) {
            $subsLog = Subscription::where('channel_id', $channelId)
                ->where('created_at', '>=', $startDate)
                ->get()
                ->groupBy(function($sub) {
                    return Carbon::parse($sub->created_at)->format('Y-m-d');
                });
        }

        $dates = [];
        $views = [];
        $watchTime = [];
        $subscribers = [];
        $revenue = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('M d');

            $views[] = isset($viewLogs[$date]) ? $viewLogs[$date]->count() : 0;
            
            $vWatch = isset($videoWatchLogs[$date]) ? $videoWatchLogs[$date]->sum('watch_duration_seconds') : 0;
            $rDwell = isset($reelDwellByDay[$date]) ? $reelDwellByDay[$date]->sum('dwell_seconds') : 0;
            $watchTime[] = round(($vWatch + $rDwell) / 3600, 2);
            
            $subscribers[] = isset($subsLog[$date]) ? $subsLog[$date]->count() : 0;
            
            $revenue[] = isset($earningsLogs[$date]) ? $earningsLogs[$date]->sum('estimated_revenue') : 0;
        }

        return compact('dates', 'views', 'watchTime', 'subscribers', 'revenue', 'totalWatchTimeHours');
    }

    public function reach(Request $request): array
    {
        $user = auth()->user();
        $days = $request->get('days', 28);
        $startDate = now()->subDays($days)->startOfDay();

        $videoIds = $user->videos()->pluck('id');
        $reelIds = $user->reels()->pluck('id');

        $viewLogs = ViewLog::where(function($q) use ($videoIds, $reelIds) {
                $q->whereIn('video_id', $videoIds)
                  ->orWhereIn('reel_id', $reelIds);
            })
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function($log) {
                return Carbon::parse($log->created_at)->format('Y-m-d');
            });

        $dailyStats = \App\Models\VideoDailyStat::whereIn('video_id', $videoIds)
            ->where('date', '>=', $startDate->toDateString())
            ->get()
            ->groupBy('date');

        $videoImpressions = $user->videos()->sum('impressions');
        $reelImpressions = ReelView::whereIn('reel_id', $reelIds)
            ->where('created_at', '>=', $startDate)
            ->count();
        $totalImpressions = $videoImpressions + $reelImpressions;
        $totalViews = $viewLogs->flatten()->count();

        $dates = [];
        $impressions = [];
        $ctr = [];

        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::parse($date)->format('M d');

            $dayViews = isset($viewLogs[$date]) ? $viewLogs[$date]->count() : 0;
            
            $dayVideoImpressions = isset($dailyStats[$date]) ? $dailyStats[$date]->sum('impressions') : 0;
            $dayReelImpressions = ReelView::whereIn('reel_id', $reelIds)->whereDate('created_at', $date)->count();
            $dayImpressions = $dayVideoImpressions + $dayReelImpressions;

            $impressions[] = $dayImpressions;
            $ctr[] = $dayImpressions > 0 ? round(($dayViews / $dayImpressions) * 100, 1) : 0;
        }

        return compact('dates', 'impressions', 'ctr', 'totalImpressions', 'totalViews');
    }

    public function realtime(Request $request): array
    {
        $user = auth()->user();
        $videoIds = $user->videos()->pluck('id');
        $reelIds = $user->reels()->pluck('id');

        $fortyEightHoursAgo = now()->subHours(48);
        $last48hViews = ViewLog::where(function($q) use ($videoIds, $reelIds) {
                $q->whereIn('video_id', $videoIds)
                  ->orWhereIn('reel_id', $reelIds);
            })
            ->where('created_at', '>=', $fortyEightHoursAgo)
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d H:00');
            });

        $hours48 = [];
        $views48 = [];
        $total48 = 0;

        for ($i = 47; $i >= 0; $i--) {
            $hour = now()->subHours($i)->format('Y-m-d H:00');
            $hours48[] = Carbon::parse($hour)->format('ga');
            $count = isset($last48hViews[$hour]) ? $last48hViews[$hour]->count() : 0;
            $views48[] = $count;
            $total48 += $count;
        }

        $sixtyMinsAgo = now()->subMinutes(60);
        $last60mViews = ViewLog::where(function($q) use ($videoIds, $reelIds) {
                $q->whereIn('video_id', $videoIds)
                  ->orWhereIn('reel_id', $reelIds);
            })
            ->where('created_at', '>=', $sixtyMinsAgo)
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d H:i');
            });

        $mins60 = [];
        $views60 = [];
        $total60 = 0;

        for ($i = 59; $i >= 0; $i--) {
            $min = now()->subMinutes($i)->format('Y-m-d H:i');
            $mins60[] = Carbon::parse($min)->format('i');
            $count = isset($last60mViews[$min]) ? $last60mViews[$min]->count() : 0;
            $views60[] = $count;
            $total60 += $count;
        }

        return [
            'last48Hours' => ['labels' => $hours48, 'data' => $views48, 'total' => $total48],
            'last60Minutes' => ['labels' => $mins60, 'data' => $views60, 'total' => $total60]
        ];
    }

    public function audience(Request $request): array
    {
        $user = auth()->user();
        $videoIds = $user->videos()->pluck('id');
        $reelIds = $user->reels()->pluck('id');
        
        $twentyEightDaysAgo = now()->subDays(28);
        
        $viewLogs = ViewLog::where(function($q) use ($videoIds, $reelIds) {
                $q->whereIn('video_id', $videoIds)
                  ->orWhereIn('reel_id', $reelIds);
            })
            ->where('created_at', '>=', $twentyEightDaysAgo)
            ->get();
            
        // Calculate Countries
        $countryCounts = $viewLogs->whereNotNull('country')->where('country', '!=', '')->countBy('country')->sortDesc()->take(5);
        $countries = [
            'labels' => $countryCounts->keys()->toArray(),
            'data' => $countryCounts->values()->toArray()
        ];
        if (empty($countries['labels'])) {
            $countries = ['labels' => ['Unknown'], 'data' => [100]];
        }
        
        // Calculate Devices
        $deviceCounts = $viewLogs->whereNotNull('device')->where('device', '!=', '')->countBy('device')->sortDesc()->take(4);
        $devices = [
            'labels' => $deviceCounts->keys()->map('ucfirst')->toArray(),
            'data' => $deviceCounts->values()->toArray()
        ];
        if (empty($devices['labels'])) {
            $devices = ['labels' => ['Mobile', 'Desktop', 'Tablet', 'TV'], 'data' => [0, 0, 0, 0]];
        }
        
        return [
            'countries' => $countries,
            'devices' => $devices
        ];
    }
}
