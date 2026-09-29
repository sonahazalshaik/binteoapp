<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only log GET requests to frontend pages (exclude admin and assets)
        if ($request->isMethod('GET') && !$request->is('admin*') && !$request->ajax()) {
            $sessionId = $request->session()->getId();
            $ip = $request->ip();
            
            // Log if not already logged in this session
            $exists = \App\Models\VisitorLog::where('session_id', $sessionId)
                ->where('created_at', '>', now()->subDay())
                ->exists();
                
            if (!$exists) {
                \App\Models\VisitorLog::create([
                    'ip_address' => $ip,
                    'user_agent' => $request->userAgent(),
                    'session_id' => $sessionId
                ]);
            }
        }

        return $next($request);
    }
}
