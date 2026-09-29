<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class ThrottleVideoViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Always count views for authenticated users (no throttle)
        if ($request->user()) {
            return $next($request);
        }

        // Get video from route
        $video = $request->route('video');
        
        if ($video) {
            $videoId = is_object($video) ? $video->id : $video;
            $cacheKey = 'viewed_video_' . $videoId . '_' . $request->ip();

            // If the user has viewed this video in the last 60 seconds, skip the view increment logic
            if (Cache::has($cacheKey)) {
                // Attach a flag to the request so the controller knows to skip incrementing
                $request->merge(['skip_view_increment' => true]);
            } else {
                // Store view in cache for 60 seconds
                Cache::put($cacheKey, true, 60);
            }
        }

        return $next($request);
    }
}
