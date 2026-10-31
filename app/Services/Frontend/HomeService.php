<?php

namespace App\Services\Frontend;

use App\Models\Video;
use App\Models\Reel;
use App\Models\Channel;
use App\Models\Category;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

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

        $videos = $this->section('videos', function () use ($baseVideoWith, $category) {
            $query = Video::forUser()->where('is_premium', 0)->with($baseVideoWith)->latest('videos.id');

            if ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            }

            return $query->paginate(12);
        }, function () use ($page) {
            return new LengthAwarePaginator([], 0, 12, (int) $page);
        });

        if (!empty($authWith)) {
            $videos->getCollection()->load($authWith);
        }

        $trendingVideos = $this->section('trendingVideos', function () use ($baseVideoWith) {
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
        }, collect());

        if (!empty($authWith)) {
            $trendingVideos->load($authWith);
        }

        $featuredVideos = $this->section('featuredVideos', function () use ($baseVideoWith) {
            return Video::forUser()->where('is_premium', 0)->with($baseVideoWith)
                ->where('is_featured', true)
                ->latest()
                ->take(12)
                ->get();
        }, collect());

        if (!empty($authWith)) {
            $featuredVideos->load($authWith);
        }

        $featuredChannels = $this->section('featuredChannels', function () {
            return Channel::where('is_featured', 1)
                ->with('user')
                ->orderBy('is_trending', 'desc')
                ->latest()
                ->take(12)
                ->get();
        }, collect());

        $subscriptionChannels = $this->section('subscriptionChannels', function () {
            return Channel::whereHas('plans', function ($q) {
                $q->where('status', 1)->where('price', '>', 0);
            })
            ->with(['user', 'plans' => function ($q) {
                $q->where('status', 1)->where('price', '>', 0);
            }])
            ->withCount(['premiumVideos as premium_videos_count'])
            ->orderBy('is_trending', 'desc')
            ->latest()
            ->take(12)
            ->get();
        }, collect());

        $reels = $this->section('reels', function () use ($baseReelWith) {
            return Reel::forUser()->with($baseReelWith)
                ->where('visibility', Status::PUBLIC)
                ->latest()
                ->take(12)
                ->get();
        }, collect());

        $reelsTotal = $this->section('reelsTotal', function () {
            return Reel::forUser()
                ->where('visibility', Status::PUBLIC)
                ->count();
        }, 0);

        if (!empty($authWith)) {
            $reels->load($authWith);
        }

        $featuredActors = $this->section('featuredActors', function () {
            return $this->safeRemember('home_featured_actors', 600, function () {
                return \App\Models\MarketPlace::where('status', 1)->where('is_featured', 1)->where('type', 'actor')
                    ->with(['subscriptions.plan'])
                    ->withCount(['ratings as ratings_count'])
                    ->withAvg('ratings as ratings_avg_rating', 'rating')
                    ->inRandomOrder()->take(10)->get();
            });
        }, collect());
        $featuredInfluencers = $this->section('featuredInfluencers', function () {
            return $this->safeRemember('home_featured_influencers', 600, function () {
                return \App\Models\MarketPlace::where('status', 1)->where('is_featured', 1)->where('type', 'influencer')
                    ->with(['subscriptions.plan'])
                    ->withCount(['ratings as ratings_count'])
                    ->withAvg('ratings as ratings_avg_rating', 'rating')
                    ->inRandomOrder()->take(10)->get();
            });
        }, collect());
        $featuredInvestors = $this->section('featuredInvestors', function () {
            return $this->safeRemember('home_featured_investors', 600, function () {
                return \App\Models\MarketPlace::where('status', 1)->where('is_featured', 1)->where('type', 'investor')
                    ->with(['subscriptions.plan'])
                    ->withCount(['ratings as ratings_count'])
                    ->withAvg('ratings as ratings_avg_rating', 'rating')
                    ->inRandomOrder()->take(10)->get();
            });
        }, collect());

        $now = now();
        // Single query for all slots (was 4 round trips) — partitioned below with identical output
        $allBanners = $this->section('banners', function () use ($now) {
            return $this->safeRemember('home_banners_all', 600, function () use ($now) {
                return \App\Models\BannerAd::whereIn('slot', ['slot1', 'slot2', 'slot3', 'slot4'])->where('status', 1)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->get();
            });
        }, collect());
        $slot1Banners = $allBanners->where('slot', 'slot1')->values();
        $slot2Banners = $allBanners->where('slot', 'slot2')->values();
        $slot3Banners = $allBanners->where('slot', 'slot3')->values();
        $slot4Banners = $allBanners->where('slot', 'slot4')->values();

        return compact(
            'videos', 'featuredActors', 'featuredInfluencers', 'featuredInvestors',
            'trendingVideos', 'reels', 'reelsTotal', 'featuredVideos', 'featuredChannels', 'subscriptionChannels',
            'slot1Banners', 'slot2Banners', 'slot3Banners', 'slot4Banners'
        );
    }

    private function safeRemember(string $key, int $ttl, \Closure $callback): mixed
    {
        return \App\Support\SafeCache::remember($key, $ttl, $callback);
    }

    /**
     * Run one homepage section with a typed fallback. Success path is
     * untouched (same query, same data, same output) — the fallback only
     * applies when the section throws, in which case that section hides via
     * the view's existing guards instead of blanking the whole page.
     */
    private function section(string $name, \Closure $callback, mixed $fallback): mixed
    {
        try {
            return $callback();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Homepage section failed: ' . $name, [
                'error' => $e->getMessage(),
            ]);
            return $fallback instanceof \Closure ? $fallback() : $fallback;
        }
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
        $query = Video::forUser()->where('is_premium', 0)->with($this->videoCardRelations())->orderBy('videos.id', 'desc');

        if ($request->category_id) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        if ($request->q) {
            $searchTerm = trim((string) $request->q);
            $tagQuery = ltrim($searchTerm, '#');

            $query->where(function ($q) use ($searchTerm, $tagQuery) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");

                if ($tagQuery !== '') {
                    $q->orWhere('title', 'LIKE', "%{$tagQuery}%")
                      ->orWhere('description', 'LIKE', "%{$tagQuery}%")
                      ->orWhere('description', 'LIKE', "%#{$tagQuery}%")
                      ->orWhereHas('tags', function ($tq) use ($tagQuery) {
                          $tq->where('tag', $tagQuery)->orWhere('tag', '#' . $tagQuery);
                      });
                }

                $q->orWhereHas('categories', function ($cq) use ($searchTerm, $tagQuery) {
                    $cq->where('categories.name', $searchTerm);
                    if ($tagQuery !== '') {
                        $cq->orWhere('categories.name', $tagQuery);
                    }
                });
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
    }
}
