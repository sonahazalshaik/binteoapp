<?php

namespace App\Services\Frontend;

use App\Models\Video;
use App\Models\Reel;
use App\Models\Channel;
use App\Models\MarketPlace;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchService
{
    public function search(Request $request): array
    {
        $query = trim((string) $request->input('q'));

        $cleanQuery = ltrim($query, '@');
        $tagQuery = ltrim($query, '#');

        $channels = Channel::select('channels.*')
            ->join('users', 'users.id', '=', 'channels.user_id')
            ->where(function ($q) use ($query, $cleanQuery) {
                if (str_starts_with($query, '@')) {
                    $q->where('users.username', $cleanQuery);
                } else {
                    $q->where('channels.name', 'LIKE', "%{$query}%")
                      ->orWhere('channels.description', 'LIKE', "%{$query}%")
                      ->orWhere('users.username', 'LIKE', "%{$cleanQuery}%")
                      ->orWhere('users.name', 'LIKE', "%{$query}%");
                }
            })
            ->where('users.status', Status::USER_ACTIVE)
            ->withCount('subscribers')
            ->when(auth()->check(), function ($q) {
                $q->withExists(['subscribers as is_subscribed' => function ($sub) {
                    $sub->where('user_id', auth()->id());
                }]);
            })
            ->latest('subscribers_count')
            ->take(10)
            ->get();

        $matchedUserIds = $channels->pluck('user_id')->toArray();

        $reels = Reel::forUser()
            ->where(function ($q) use ($query, $cleanQuery, $matchedUserIds, $tagQuery) {
                $q->where('reels.title', 'LIKE', "%{$query}%")
                  ->orWhere('reels.description', 'LIKE', "%{$query}%");

                if ($tagQuery !== '') {
                    $q->orWhere('reels.title', 'LIKE', "%{$tagQuery}%")
                      ->orWhere('reels.description', 'LIKE', "%{$tagQuery}%")
                      ->orWhere('reels.description', 'LIKE', "%#{$tagQuery}%")
                      ->orWhereHas('hashtags', function ($hq) use ($tagQuery) {
                          $hq->where('hashtag', $tagQuery)->orWhere('hashtag', '#' . $tagQuery);
                      })
                      ->orWhereHas('tags', function ($tq) use ($tagQuery) {
                          $tq->where('tag', $tagQuery)->orWhere('tag', '#' . $tagQuery);
                      });
                }

                $q->orWhereHas('user', function ($uq) use ($cleanQuery, $query) {
                      $uq->where('username', 'LIKE', "%{$cleanQuery}%")
                         ->orWhere('name', 'LIKE', "%{$query}%");
                  })
                  ->orWhereIn('reels.user_id', $matchedUserIds)
                  ->orWhereHas('category', function ($cq) use ($query, $tagQuery) {
                      $cq->where('categories.name', $query);
                      if ($tagQuery !== '') {
                          $cq->orWhere('categories.name', $tagQuery);
                      }
                  });
            })
            ->with('user.channel')
            ->withExists(['likes as is_liked' => function ($lq) {
                $lq->where('user_id', auth()->id());
            }])
            ->latest()
            ->take(12)
            ->get();

        $videos = Video::forUser()->where('is_premium', 0)
            ->where(function ($q) use ($query, $cleanQuery, $matchedUserIds, $tagQuery) {
                $q->where('videos.title', 'LIKE', "%{$query}%")
                  ->orWhere('videos.description', 'LIKE', "%{$query}%");

                if ($tagQuery !== '') {
                    $q->orWhere('videos.title', 'LIKE', "%{$tagQuery}%")
                      ->orWhere('videos.description', 'LIKE', "%{$tagQuery}%")
                      ->orWhere('videos.description', 'LIKE', "%#{$tagQuery}%")
                      ->orWhereHas('tags', function ($tq) use ($tagQuery) {
                          $tq->where('tag', $tagQuery)->orWhere('tag', '#' . $tagQuery);
                      });
                }

                $q->orWhereHas('user', function ($uq) use ($cleanQuery, $query) {
                      $uq->where('username', 'LIKE', "%{$cleanQuery}%")
                         ->orWhere('name', 'LIKE', "%{$query}%");
                  })
                  ->orWhereIn('videos.user_id', $matchedUserIds)
                  ->orWhereHas('categories', function ($cq) use ($query, $tagQuery) {
                      $cq->where('categories.name', $query);
                      if ($tagQuery !== '') {
                          $cq->orWhere('categories.name', $tagQuery);
                      }
                  });
            })
            ->whereNotExists(function ($sq) {
                $sq->select(DB::raw(1))
                   ->from('copyright_strikes')
                   ->whereColumn('copyright_strikes.video_id', 'videos.id')
                   ->where('copyright_strikes.status', 'active');
            })
            ->with('user.channel')
            ->withExists(['likes as is_liked' => function ($lq) {
                $lq->where('user_id', auth()->id());
            }])
            ->latest()
            ->paginate(12);

        $talents = MarketPlace::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('business_name', 'LIKE', "%{$query}%")
                  ->orWhere('more_info', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->paginate(12);

        $searchQuery = $query;
        return compact('searchQuery', 'query', 'channels', 'reels', 'videos', 'talents');
    }
}
