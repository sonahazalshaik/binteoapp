<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Route;

class LogSystemPerformance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);
        $durationMs = round(($endTime - $startTime) * 1000);

        // Only log if it's a web or api request (avoid logging static assets if served through Laravel)
        if (!$request->expectsJson() && $request->isMethod('GET') && str_contains($request->url(), 'debugbar')) {
             return $response;
        }

        try {
            $logData = [
                'user_id' => auth()->id(),
                'route_name' => Route::currentRouteName(),
                'url' => $request->fullUrl(),
                'response_time_ms' => $durationMs,
                'status_code' => $response->getStatusCode(),
                'is_error' => $response->isServerError() || $response->isClientError(),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ];
            \App\Jobs\WriteSystemLog::dispatch($logData);
        } catch (\Exception $e) {
            // Fail silently to not break the app if logging fails
        }

        return $response;
    }
}
