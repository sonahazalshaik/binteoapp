<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('admin')->check()) {
            return $next($request);
        }

        // Save the requested admin URL as intended so they redirect back after successful login
        session(['url.intended' => $request->fullUrl()]);

        return redirect()->route('admin.login');
    }
}
