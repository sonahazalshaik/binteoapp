<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssSanitization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip sanitization for upload-related routes to prevent data corruption
        if ($request->is('videos/prepare*') || $request->is('reels/prepare*') || $request->is('videos/direct-upload*') || $request->is('reels/direct-upload*') || $request->is('tus*')) {
            return $next($request);
        }

        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if (is_string($value) && !in_array($key, ['description', 'content', 'body'])) {
                // Remove potential script tags and other malicious HTML
                $value = strip_tags($value);
                
                // Convert special characters to HTML entities for storage
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
