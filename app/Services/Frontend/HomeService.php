<?php

namespace App\Services\Frontend;

use App\Models\Video;
use App\Models\Reel;
use App\Models\Channel;
use App\Models\Category;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    public function getHomepageData(Request $request): array
    {
        $baseVideoWith = ['user.channel', 'reports'];
        $baseReelWith = ['user.channel', 'reports'];
        
        $authWith = [];
        if (auth()->check()) {
            $authWith = ['authLikes', 'authPlaylists'];
        }

        $page = $request->get('page', 1);
        $category = $request->get('category', '');
        $userId = auth()->id() ?? 'guest';

        $cacheKey = "home_videos_v3_cat_{$category}_page_{$page}_user_{$userId}";
        $videos = $this->safeRemember($cacheKey, 60, function () use ($baseVideoWith, $category) {
            $query = Video::forUser()->where('is_premium', 0)->with($baseVideoWith)->latest('videos.id');

            if ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            }

            return $query->paginate(12);
        });

        if (!empty($authWith)) {
            $videos->getCollection()->load($authWith);
        }

        $trendingVideos = $this->safeRemember("home_trending_videos_v3_user_{$userId}", 300, function () use ($baseVideoWith) {
            return Video::forUser()->where('is_premium', 0)->with($baseVideoWith)
                ->orderBy('is_trending', 'desc')
                ->withCount(['viewLogs as daily_views' => function ($q) {
                    $q->where('created_at', '>=', now()->subDay());
                }])
                ->orderBy('daily_views', 'desc')
                ->orderBy('views_count', 'desc')
                ->take(20)
                ->get()
                ->shuffle()
                ->take(12);
        });

        if (!empty($authWith)) {
            $trendingVideos->load($authWith);
        }

        $featuredVideos = $this->safeRemember("home_featured_videos_v3_user_{$userId}", 300, function () use ($baseVideoWith) {
            return Video::forUser()->where('is_premium', 0)->with($baseVideoWith)
                ->where('is_featured', true)
                ->latest()
                ->take(12)
                ->get();
        });

        if (!empty($authWith)) {
            $featuredVideos->load($authWith);
        }

        $featuredChannels = $this->safeRemember('home_featured_channels', 300, function () {
            return Channel::where('is_featured', 1)
                ->with('user')
                ->orderBy('is_trending', 'desc')
                ->latest()
                ->take(12)
                ->get();
        });

        $reels = $this->safeRemember("home_reels_v3_user_{$userId}", 120, function () use ($baseReelWith) {
            return Reel::forUser()->with($baseReelWith)
                ->where('visibility', Status::PUBLIC)
                ->latest()
                ->take(12)
                ->get();
        });

        if (!empty($authWith)) {
            $reels->load($authWith);
        }

        $featuredActors = $this->safeRemember('home_featured_actors', 600, function () {
            return \App\Models\MarketPlace::where('status', 1)->where('is_featured', 1)->where('type', 'actor')
                ->with(['subscriptions.plan'])
                ->withCount(['ratings as ratings_count'])
                ->withAvg('ratings as ratings_avg_rating', 'rating')
                ->inRandomOrder()->take(10)->get();
        });
        $featuredInfluencers = $this->safeRemember('home_featured_influencers', 600, function () {
            return \App\Models\MarketPlace::where('status', 1)->where('is_featured', 1)->where('type', 'influencer')
                ->with(['subscriptions.plan'])
                ->withCount(['ratings as ratings_count'])
                ->withAvg('ratings as ratings_avg_rating', 'rating')
                ->inRandomOrder()->take(10)->get();
        });
        $featuredInvestors = $this->safeRemember('home_featured_investors', 600, function () {
            return \App\Models\MarketPlace::where('status', 1)->where('is_featured', 1)->where('type', 'investor')
                ->with(['subscriptions.plan'])
                ->withCount(['ratings as ratings_count'])
                ->withAvg('ratings as ratings_avg_rating', 'rating')
                ->inRandomOrder()->take(10)->get();
        });

        $now = now();
        $slot1Banners = $this->safeRemember('home_banners_slot1', 300, function () use ($now) {
            return \App\Models\BannerAd::where('slot', 'slot1')->where('status', 1)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->get();
        });
        $slot2Banners = $this->safeRemember('home_banners_slot2', 300, function () use ($now) {
            return \App\Models\BannerAd::where('slot', 'slot2')->where('status', 1)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->get();
        });
        $slot3Banners = $this->safeRemember('home_banners_slot3', 300, function () use ($now) {
            return \App\Models\BannerAd::where('slot', 'slot3')->where('status', 1)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->get();
        });
        $slot4Banners = $this->safeRemember('home_banners_slot4', 300, function () use ($now) {
            return \App\Models\BannerAd::where('slot', 'slot4')->where('status', 1)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->get();
        });

        return compact(
            'videos', 'featuredActors', 'featuredInfluencers', 'featuredInvestors',
            'trendingVideos', 'reels', 'featuredVideos', 'featuredChannels',
            'slot1Banners', 'slot2Banners', 'slot3Banners', 'slot4Banners'
        );
    }

    private function safeRemember(string $key, int $ttl, \Closure $callback): mixed
    {
        return \App\Support\SafeCache::remember($key, $ttl, $callback);
    }

    public function getNotifications(): array
    {
        $user = auth()->user();
        if (!$user) return ['alerts' => [], 'unread_count' => 0];

        $query = $user->userNotifications()
            ->whereNotNull('title')
            ->where('title', '!=', '');

        $notifications = (clone $query)->latest()->take(20)->get()->map(function ($n) {
            $url = $n->click_url;
            $clickUrl = '#';
            if ($url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $parsed = parse_url($url);
                    $relative = ($parsed['path'] ?? '') . (isset($parsed['query']) ? '?' . $parsed['query'] : '') . (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
                    $clickUrl = $relative ?: '/';
                } else {
                    $clickUrl = $url;
                }
            }
            return [
                'title' => $n->title,
                'click_url' => $clickUrl,
                'created_at' => $n->created_at->diffForHumans(),
            ];
        });

        return [
            'alerts' => $notifications,
            'unread_count' => (clone $query)->where('is_read', 0)->count(),
        ];
    }

    public function markNotificationsRead(): void
    {
        auth()->user()->userNotifications()->where('is_read', 0)->update(['is_read' => 1]);
    }

    public function getCategoryVideos(string $slug): array
    {
        $category = Category::where('slug', $slug)
            ->orWhere('slug', \Illuminate\Support\Str::singular($slug))
            ->orWhere('slug', \Illuminate\Support\Str::plural($slug))
            ->orWhere('name', 'LIKE', $slug)
            ->firstOrFail();

        $pageTitle = $category->name;
        $catId = $category->id;
        $catSlug = $category->slug;

        $videos = Video::forUser()->where('is_premium', 0)->with($this->videoCardRelations())
            ->where(function ($query) use ($catId, $catSlug) {
                $query->where('category_id', $catId)
                      ->orWhereHas('categories', function ($q) use ($catId, $catSlug) {
                          $q->where('categories.id', $catId)
                            ->orWhere('categories.slug', $catSlug);
                      });
            })
            ->latest('videos.id')
            ->paginate(12);

        $reels = Reel::forUser()->with($this->reelCardRelations())
            ->where(function ($query) use ($catId, $catSlug) {
                $query->where('category_id', $catId)
                      ->orWhereHas('category', function ($q) use ($catId, $catSlug) {
                          $q->where('categories.id', $catId)
                            ->orWhere('categories.slug', $catSlug);
                      });
            })
            ->latest('reels.id')
            ->take(12)
            ->get();

        return compact('category', 'videos', 'reels', 'pageTitle');
    }

    private function videoCardRelations(): array
    {
        $with = ['user.channel', 'reports'];
        if (auth()->check()) {
            $with[] = 'authLikes';
            $with[] = 'authPlaylists';
        }
        return $with;
    }

    private function reelCardRelations(): array
    {
        $with = ['user.channel', 'reports'];
        if (auth()->check()) {
            $with[] = 'authLikes';
            $with[] = 'authPlaylists';
        }
        return $with;
    }

    public function fetchVideos(Request $request): array
    {
        $cacheKey = 'fetch_videos_' . md5(serialize($request->only(['page', 'category_id', 'q'])));

        return $this->safeRemember($cacheKey, 30, function () use ($request) {
            $query = Video::forUser()->where('is_premium', 0)->with(['user.channel'])->orderBy('videos.id', 'desc');

            if ($request->category_id) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('categories.id', $request->category_id);
                });
            }

            if ($request->q) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'LIKE', "%{$request->q}%")
                      ->orWhere('description', 'LIKE', "%{$request->q}%");
                });
            }

            $videos = $query->simplePaginate(12);

            $html = '';
            foreach ($videos as $video) {
                $html .= view('frontend.partials.video_card', compact('video'))->render();
            }

            return [
                'status' => 'success',
                'html' => $html,
                'has_more' => $videos->hasMorePages(),
            ];
        });
    }
}
