<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AnalyticsDailyRollup;
use App\Models\VideoWatchLog;
use App\Models\UserSession;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AggregateDailyAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:aggregate {--date= : The date to aggregate in YYYY-MM-DD format (defaults to yesterday)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregates raw telemetry data into daily analytics rollups for the Admin Dashboard.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateString = $this->option('date');
        $targetDate = $dateString ? Carbon::parse($dateString) : Carbon::yesterday();
        $date = $targetDate->toDateString();

        $this->info("Aggregating analytics for date: {$date}");

        // 1. Total Uploads for the day
        $totalUploads = Video::whereDate('created_at', $date)->count();

        // 2. MAU (Monthly Active Users) - Users with a session in the last 30 days up to the target date
        $thirtyDaysAgo = $targetDate->copy()->subDays(30);
        $mau = UserSession::where('started_at', '>=', $thirtyDaysAgo)
            ->where('started_at', '<=', $targetDate->copy()->endOfDay())
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        // 3. Average Watch Time Per User (in seconds)
        // We get total watch time for the day, divided by number of unique users who watched.
        $totalWatchTime = VideoWatchLog::where('date', $date)->sum('watch_duration_seconds');
        $uniqueWatchUsers = VideoWatchLog::where('date', $date)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');
            
        $avgWatchTime = $uniqueWatchUsers > 0 ? (int) ($totalWatchTime / $uniqueWatchUsers) : 0;

        // 4. Average Session Duration (in seconds)
        $avgSessionDuration = UserSession::whereDate('started_at', $date)
            ->whereNotNull('duration_seconds')
            ->avg('duration_seconds');

        // 5. Video Completion Rate (VCR)
        $avgVcr = VideoWatchLog::where('date', $date)->avg('completion_percentage');

        // Upsert the Rollup
        AnalyticsDailyRollup::updateOrCreate(
            ['date' => $date],
            [
                'total_uploads' => $totalUploads,
                'mau' => $mau,
                'avg_watch_time_seconds' => $avgWatchTime,
                'avg_session_duration_seconds' => $avgSessionDuration ? (int) $avgSessionDuration : 0,
                'avg_vcr_percentage' => $avgVcr ? (float) $avgVcr : 0.0,
            ]
        );

        $this->info('Daily analytics aggregation completed successfully.');
        return Command::SUCCESS;
    }
}
