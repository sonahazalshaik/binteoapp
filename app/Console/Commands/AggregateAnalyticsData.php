<?php

namespace App\Console\Commands;

use App\Models\AnalyticsContentSummary;
use App\Models\AnalyticsCreatorSummary;
use App\Models\AnalyticsPlatformSummary;
use App\Models\AnalyticsRetentionSummary;
use App\Models\SystemLog;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoEvent;
use App\Models\WatchHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AggregateAnalyticsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:aggregate {date? : The date to aggregate (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregates raw analytics logs into summary tables for performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateStr = $this->argument('date') ?: Carbon::yesterday()->toDateString();
        $date = Carbon::parse($dateStr);
        $this->info('Aggregating analytics for: '.$date->toDateString());

        $this->aggregatePlatformMetrics($date);
        $this->aggregateContentMetrics($date);
        $this->aggregateRetentionMetrics($date);
        $this->aggregateCreatorMetrics($date);

        $this->info('Aggregation complete.');
    }

    private function aggregatePlatformMetrics($date)
    {
        $this->info('Processing Platform Metrics...');

        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();

        // DAU: Unique users with any system activity
        $dau = SystemLog::whereBetween('created_at', [$dayStart, $dayEnd])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count();

        // MAU: Unique users in last 30 days
        $mau = SystemLog::whereBetween('created_at', [$date->copy()->subDays(30), $dayEnd])
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count();

        // New Users
        $newUsers = User::whereBetween('created_at', [$dayStart, $dayEnd])->count();

        // Active Creators: Users who uploaded or had video events
        $activeCreators = Video::whereBetween('created_at', [$dayStart, $dayEnd])
            ->distinct('user_id')
            ->count();

        // Videos Uploaded
        $videosUploaded = Video::whereBetween('created_at', [$dayStart, $dayEnd])->count();

        // Avg Session Duration (Simplified: Avg time between first and last log for each user)
        $avgSessionDuration = DB::table('system_logs')
            ->select(DB::raw('user_id, TIMESTAMPDIFF(SECOND, MIN(created_at), MAX(created_at)) as duration'))
            ->whereBetween('created_at', [$dayStart, $dayEnd])
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->get()
            ->avg('duration') ?: 0;

        // Growth: Signups by Source
        $signupsOrganic = User::whereBetween('created_at', [$dayStart, $dayEnd])->where('traffic_source', 'organic')->count();
        $signupsReferral = User::whereBetween('created_at', [$dayStart, $dayEnd])->where('traffic_source', 'referral')->count();
        $signupsAds = User::whereBetween('created_at', [$dayStart, $dayEnd])->where('traffic_source', 'ads')->count();

        // Monetization: Revenue
        // 1. Ads (if you have video_earnings or similar table)
        // For demonstration, assuming DB::table('video_earnings') exists or we use a stub if not
        $revenueAds = 0;
        if (Schema::hasTable('video_earnings')) {
            $revenueAds = DB::table('video_earnings')->whereBetween('created_at', [$dayStart, $dayEnd])->sum('estimated_revenue') ?? 0;
        }

        // 2. Subscriptions (OTT)
        $revenueSubs = 0;
        if (Schema::hasTable('ott_subscriptions')) {
            $revenueSubs = DB::table('ott_subscriptions')->whereBetween('created_at', [$dayStart, $dayEnd])->sum('price') ?? 0;
        }

        // 3. Purchases (Purchased Videos)
        $revenuePurchases = 0;
        if (Schema::hasTable('purchased_videos')) {
            $revenuePurchases = DB::table('purchased_videos')->whereBetween('created_at', [$dayStart, $dayEnd])->sum('price') ?? 0;
        }

        // 4. Deposits (Direct Payments)
        $revenueDeposits = DB::table('deposits')
            ->where('status', 1)
            ->whereBetween('created_at', [$dayStart, $dayEnd])
            ->sum('amount') ?? 0;

        $totalRevenue = $revenueAds + $revenueSubs + $revenuePurchases + $revenueDeposits;

        AnalyticsPlatformSummary::updateOrCreate(
            ['recorded_at' => $date->toDateString()],
            [
                'dau' => $dau,
                'mau' => $mau,
                'new_users' => $newUsers,
                'active_creators' => $activeCreators,
                'videos_uploaded' => $videosUploaded,
                'avg_session_duration' => $avgSessionDuration,
                'revenue_ads' => $revenueAds,
                'revenue_subscriptions' => $revenueSubs,
                'revenue_purchases' => $revenuePurchases,
                'total_revenue' => $totalRevenue,
                'signups_organic' => $signupsOrganic,
                'signups_referral' => $signupsReferral,
                'signups_ads' => $signupsAds,
            ]
        );
    }

    private function aggregateContentMetrics($date)
    {
        $this->info('Processing Content Metrics...');

        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();

        // Get all videos active on this day
        $activeVideoIds = \App\Models\VideoWatchLog::whereBetween('created_at', [$dayStart, $dayEnd])
            ->whereNotNull('video_id')
            ->distinct('video_id')
            ->pluck('video_id');

        foreach ($activeVideoIds as $videoId) {
            $dailyViews = \App\Models\VideoWatchLog::where('video_id', $videoId)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            $avgWatchTime = \App\Models\VideoWatchLog::where('video_id', $videoId)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->avg('watch_duration_seconds') ?: 0;

            $completions = \App\Models\VideoWatchLog::where('video_id', $videoId)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->where('completion_percentage', '>=', 95)
                ->count();

            $completionRate = $dailyViews > 0 ? ($completions / $dailyViews) * 100 : 0;

            // Calculate rewatches
            $rewatchCount = 0;

            // First: Use VideoEvent (play events) if available
            $playCount = VideoEvent::where('video_id', $videoId)
                ->where('event_type', 'play')
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            if ($playCount > 0) {
                $uniquePlayers = VideoEvent::where('video_id', $videoId)
                    ->where('event_type', 'play')
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->distinct('user_id')
                    ->count('user_id');

                $rewatchCount = max(0, $playCount - $uniquePlayers);
            } else {
                // Fallback: Compute from VideoWatchLog
                // Users who completed the video on a previous day and came back today
                $rewatchCount = \App\Models\VideoWatchLog::where('video_id', $videoId)
                    ->where('completion_percentage', '>=', 95)
                    ->whereDate('created_at', '<', $date->toDateString())
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count();
            }

            AnalyticsContentSummary::updateOrCreate(
                ['video_id' => $videoId, 'recorded_at' => $date->toDateString()],
                [
                    'daily_views' => $dailyViews,
                    'avg_watch_time' => $avgWatchTime,
                    'completion_rate' => $completionRate,
                    'rewatch_count' => $rewatchCount,
                ]
            );
        }
    }

    private function aggregateRetentionMetrics($date)
    {
        $this->info('Processing Retention Metrics...');

        // We calculate retention for cohorts that signed up 1, 7, and 30 days ago
        $intervals = [1, 7, 30];

        foreach ($intervals as $days) {
            $cohortDate = $date->copy()->subDays($days);
            $cohortDayStart = $cohortDate->copy()->startOfDay();
            $cohortDayEnd = $cohortDate->copy()->endOfDay();

            // Total users who joined on cohortDate
            $totalInCohort = User::whereBetween('created_at', [$cohortDayStart, $cohortDayEnd])->count();

            if ($totalInCohort > 0) {
                // Users from cohort who were active on current date
                // We check both SystemLogs AND User last_seen for maximum accuracy
                $retainedUsers = DB::table('users')
                    ->whereBetween('created_at', [$cohortDayStart, $cohortDayEnd])
                    ->where(function ($q) use ($date) {
                        $q->whereExists(function ($query) use ($date) {
                            $query->select(DB::raw(1))
                                ->from('system_logs')
                                ->whereColumn('system_logs.user_id', 'users.id')
                                ->whereBetween('system_logs.created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()]);
                        })
                            ->orWhereBetween('last_seen', [$date->copy()->startOfDay(), $date->copy()->endOfDay()]);
                    })
                    ->count();

                AnalyticsRetentionSummary::updateOrCreate(
                    ['cohort_date' => $cohortDate->toDateString(), 'day_n' => $days],
                    [
                        'total_users' => $totalInCohort,
                        'retained_users' => $retainedUsers,
                        'retention_rate' => ($retainedUsers / $totalInCohort) * 100,
                    ]
                );
            }
        }
    }

    private function aggregateCreatorMetrics($date)
    {
        $this->info('Processing Creator Metrics...');

        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();

        // Get all unique creator IDs who had ANY activity today:
        // 1. Had video views
        // 2. Gained subscribers
        // 3. Earned revenue
        $creatorIdsWithViews = DB::table('videos')
            ->join('watch_history', 'videos.id', '=', 'watch_history.video_id')
            ->whereBetween('watch_history.updated_at', [$dayStart, $dayEnd])
            ->distinct('videos.user_id')
            ->pluck('videos.user_id')
            ->toArray();

        $creatorIdsWithSubs = DB::table('subscriptions')
            ->join('channels', 'subscriptions.channel_id', '=', 'channels.id')
            ->whereBetween('subscriptions.created_at', [$dayStart, $dayEnd])
            ->distinct('channels.user_id')
            ->pluck('channels.user_id')
            ->toArray();

        $creatorIdsWithEarnings = [];
        if (Schema::hasTable('video_earnings')) {
            $creatorIdsWithEarnings = DB::table('video_earnings')
                ->join('videos', 'video_earnings.video_id', '=', 'videos.id')
                ->whereBetween('video_earnings.created_at', [$dayStart, $dayEnd])
                ->distinct('videos.user_id')
                ->pluck('videos.user_id')
                ->toArray();
        }

        $allCreatorIds = array_unique(array_merge($creatorIdsWithViews, $creatorIdsWithSubs, $creatorIdsWithEarnings));

        foreach ($allCreatorIds as $creatorId) {
            // Aggregate metrics for this creator's videos
            $stats = DB::table('videos')
                ->join('watch_history', 'videos.id', '=', 'watch_history.video_id')
                ->where('videos.user_id', $creatorId)
                ->whereBetween('watch_history.updated_at', [$dayStart, $dayEnd])
                ->select(
                    DB::raw('COUNT(*) as total_views'),
                    DB::raw('SUM(watch_history.progress_seconds) / 60 as watch_time_minutes')
                )
                ->first();

            // Subscribers gained today
            $subsGained = DB::table('subscriptions')
                ->join('channels', 'subscriptions.channel_id', '=', 'channels.id')
                ->where('channels.user_id', $creatorId)
                ->whereBetween('subscriptions.created_at', [$dayStart, $dayEnd])
                ->count();

            // Earnings
            $earnings = 0;
            if (Schema::hasTable('video_earnings')) {
                $earnings = DB::table('video_earnings')
                    ->join('videos', 'video_earnings.video_id', '=', 'videos.id')
                    ->where('videos.user_id', $creatorId)
                    ->whereBetween('video_earnings.created_at', [$dayStart, $dayEnd])
                    ->sum('video_earnings.estimated_revenue') ?: 0;
            }

            AnalyticsCreatorSummary::updateOrCreate(
                ['user_id' => $creatorId, 'recorded_at' => $date->toDateString()],
                [
                    'views' => $stats->total_views ?? 0,
                    'watch_time_minutes' => $stats->watch_time_minutes ?? 0,
                    'subscribers_gained' => $subsGained,
                    'earnings' => $earnings,
                ]
            );
        }
    }
}
