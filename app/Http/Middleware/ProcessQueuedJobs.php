<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessQueuedJobs
{
    /**
     * Let the request pass through normally — no delay for the user.
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * AFTER the response is sent to the browser, process pending jobs.
     * The user's page has already loaded — this runs invisibly in the background.
     * 
     * Uses a cache lock to prevent multiple visitors from running the worker
     * at the same time (only 1 worker runs at a time).
     */
    public function terminate(Request $request, $response)
    {
        // Skip for AJAX/API requests to avoid overhead on frequent calls
        if ($request->ajax() || $request->is('api/*')) {
            return;
        }

        // Only run once every 30 seconds (not on every single page load),
        // except for the Bunny Webhook request which must process immediately.
        $isWebhook = $request->is('webhooks/bunny') || $request->is('*/webhooks/bunny');
        $lockKey = 'auto_queue_lock';
        if (!$isWebhook && Cache::has($lockKey)) {
            return;
        }

        // Flush HTTP connection to browser immediately if running under FastCGI / FPM
        // so the user's page finishes reloading in <1 sec while queued commands process.
        if (function_exists('fastcgi_finish_request')) {
            @fastcgi_finish_request();
        }

        try {
            $pendingCount = DB::table('jobs')->count();
            if ($pendingCount === 0) {
                return;
            }

            // Set lock for 15 seconds so multiple concurrent visitors don't spawn duplicate workers
            Cache::put($lockKey, true, 15);

            // Process pending jobs until queue is empty (max 10 jobs per visit batch)
            Artisan::call('queue:work', [
                '--queue' => 'high,default',
                '--stop-when-empty' => true,
                '--max-jobs' => 10,
                '--tries' => 3,
                '--quiet' => true,
            ]);

            Log::info('[AUTO-QUEUE] Processed jobs on page visit', [
                'pending_before' => $pendingCount,
                'remaining' => DB::table('jobs')->count(),
            ]);
        } catch (\Throwable $e) {
            Cache::forget($lockKey);
            Log::error('[AUTO-QUEUE] Error: ' . $e->getMessage());
        }
    }
}
