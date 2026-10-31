<?php

namespace App\Services\Frontend;

use App\Models\Video;
use App\Models\ViewLog;
use App\Models\AdImpression;
use App\Models\UserSession;
use App\Models\VideoWatchLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    public function pingVideo(Request $request): array
    {
        $request->validate([
            'session_token' => 'required|string',
            'video_id' => 'nullable|integer',
            'reel_id' => 'nullable|integer',
            'current_time' => 'required|numeric',
            'total_duration' => 'required|numeric',
            'watch_increment' => 'nullable|numeric',
        ]);

        if (!$request->video_id && !$request->reel_id) {
            return ['status' => 'error', 'message' => 'Video ID or Reel ID is required'];
        }

        $userId = auth()->check() ? auth()->id() : null;
        $date = Carbon::today()->toDateString();
        $percentage = $request->total_duration > 0 ? min(100, ($request->current_time / $request->total_duration) * 100) : 0;

        $watchLog = VideoWatchLog::firstOrCreate(
            [
                'session_token' => $request->session_token,
                'video_id' => $request->video_id,
                'reel_id' => $request->reel_id,
                'date' => $date,
            ],
            [
                'user_id' => $userId,
                'watch_duration_seconds' => 0,
                'completion_percentage' => 0,
            ]
        );

        if ($percentage > $watchLog->completion_percentage) {
            $watchLog->completion_percentage = $percentage;
        }

        $increment = $request->input('watch_increment', 0);
        if ($increment > 0) {
            $watchLog->watch_duration_seconds += $increment;
        }

        if ($userId && !$watchLog->user_id) {
            $watchLog->user_id = $userId;
        }

        $watchLog->save();

        Log::info("Telemetry: Video/Reel Watched", [
            'video_id' => $request->video_id,
            'reel_id' => $request->reel_id,
            'user_id' => $userId,
            'session_token' => $request->session_token,
            'duration_added' => $increment,
            'total_watch_seconds' => $watchLog->watch_duration_seconds,
            'completion_percentage' => $watchLog->completion_percentage
        ]);

        return ['status' => 'success'];
    }

    public function pingSession(Request $request): array
    {
        $request->validate([
            'session_token' => 'required|string',
            'action' => 'required|in:start,ping,end',
        ]);

        $userId = auth()->check() ? auth()->id() : null;

        $session = UserSession::firstOrCreate(
            ['session_token' => $request->session_token],
            [
                'user_id' => $userId,
                'started_at' => now(),
            ]
        );

        if ($userId && !$session->user_id) {
            $session->user_id = $userId;
        }

        if ($request->action === 'end' || $request->action === 'ping') {
            $session->ended_at = now();
            $session->duration_seconds = max(0, $session->started_at->diffInSeconds($session->ended_at));
        }

        $session->save();

        Log::info("Telemetry: Session Ping", [
            'action' => $request->action,
            'session_token' => $request->session_token,
            'user_id' => $userId,
            'duration_seconds' => $session->duration_seconds ?? 0
        ]);

        return ['status' => 'success'];
    }

    public function logVideoEvent(Request $request): array
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'event_type' => 'required|string',
            'value' => 'nullable|string',
            'seconds_at' => 'nullable|numeric'
        ]);

        \App\Models\VideoEvent::create([
            'user_id' => auth()->id(),
            'video_id' => $request->video_id,
            'event_type' => $request->event_type,
            'value' => $request->value,
            'seconds_at' => $request->seconds_at ?? 0,
        ]);

        return ['success' => true];
    }

    public function updateWatchProgress(Request $request): array
    {
        $request->validate([
            'video_id' => 'required|exists:videos,id',
            'progress_seconds' => 'required|numeric',
            'total_duration' => 'required|numeric',
            'is_completed' => 'nullable|boolean',
            'device_type' => 'nullable|string'
        ]);

        $delta = $request->watch_delta ?? 0;
        $history = null;

        if (auth()->check()) {
            $history = \App\Models\WatchHistory::where('user_id', auth()->id())
                ->where('video_id', $request->video_id)
                ->first();

            if ($history) {
                $oldPercent = $request->total_duration > 0 ? ($history->progress_seconds / $request->total_duration) * 100 : 0;
                $newPercent = $request->total_duration > 0 ? ($request->progress_seconds / $request->total_duration) * 100 : 0;

                $milestones = [25, 50, 75, 100];
                foreach ($milestones as $m) {
                    if ($oldPercent < $m && $newPercent >= $m) {
                        Video::where('id', $request->video_id)->increment("retention_{$m}");
                    }
                }

                if ($delta <= 0) {
                    $delta = max(0, $request->progress_seconds - $history->progress_seconds);
                }

                $history->update([
                    'progress_seconds' => $request->progress_seconds,
                    'total_duration' => $request->total_duration,
                    'is_completed' => $request->is_completed ?? false,
                    'device_type' => $request->device_type,
                    'last_watched_at' => now(),
                ]);
            } else {
                if ($delta <= 0) {
                    $delta = $request->progress_seconds;
                }

                $history = \App\Models\WatchHistory::create([
                    'user_id' => auth()->id(),
                    'video_id' => $request->video_id,
                    'progress_seconds' => $request->progress_seconds,
                    'total_duration' => $request->total_duration,
                    'is_completed' => $request->is_completed ?? false,
                    'device_type' => $request->device_type,
                    'last_watched_at' => now(),
                ]);

                $newPercent = $request->total_duration > 0 ? ($request->progress_seconds / $request->total_duration) * 100 : 0;
                foreach ([25, 50, 75, 100] as $m) {
                    if ($newPercent >= $m) Video::where('id', $request->video_id)->increment("retention_{$m}");
                }
            }
        } else {
            $sessionKey = "video_retention_{$request->video_id}";
            $crossed = session()->get($sessionKey, []);

            $newPercent = $request->total_duration > 0 ? ($request->progress_seconds / $request->total_duration) * 100 : 0;
            foreach ([25, 50, 75, 100] as $m) {
                if ($newPercent >= $m && !in_array($m, $crossed)) {
                    Video::where('id', $request->video_id)->increment("retention_{$m}");
                    $crossed[] = $m;
                }
            }
            session()->put($sessionKey, $crossed);

            if ($delta <= 0) {
                $delta = min(10, $request->total_duration);
            }
        }

        if ($delta > 0) {
            Video::where('id', $request->video_id)->increment('total_watch_time', $delta);
        }

        // Single source view count: increment views_count once per user/session when they actually watch
        // Mirror Bunny CDN view count logic so app views_count stays in sync
        $shouldCountView = false;
        $viewKey = auth()->check() 
            ? "video_viewed_{$request->video_id}_user_" . auth()->id()
            : "video_viewed_{$request->video_id}_" . session()->getId();

        if (!session()->has($viewKey)) {
            $watchedEnough = $request->progress_seconds >= 3 || ($request->total_duration > 0 && ($request->progress_seconds / $request->total_duration) >= 0.05);
            if ($watchedEnough) {
                $shouldCountView = true;
                session()->put($viewKey, true);
                Video::where('id', $request->video_id)->increment('views_count');
                Video::where('id', $request->video_id)->increment('impressions');
                
                try {
                    \App\Models\VideoDailyStat::firstOrCreate(
                        ['video_id' => $request->video_id, 'date' => \Carbon\Carbon::today()->toDateString()],
                        ['impressions' => 0, 'views' => 0]
                    )->incrementEach(['views' => 1, 'impressions' => 1]);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Failed to log daily view/impression: ' . $e->getMessage());
                }
            }
        }

        $videoObj = Video::select('id', 'views_count')->find($request->video_id);

        Log::info("Telemetry: Watch Progress Update", [
            'video_id' => $request->video_id,
            'user_id' => auth()->id(),
            'progress_seconds' => $request->progress_seconds,
            'total_duration' => $request->total_duration,
            'view_counted_now' => $shouldCountView,
            'total_views_count' => $videoObj ? $videoObj->views_count : null,
            'session_key' => $viewKey
        ]);

        return [
            'success' => true,
            'is_completed' => $history ? $history->is_completed : ($request->is_completed ?? false)
        ];
    }
}
