<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceUpdateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $gs = gs();

        // Skip for admin, update-required routes, or API
        if ($request->is('admin*') || $request->is('update-required*') || $request->is('update-now*') || $request->is('api*')) {
            return $next($request);
        }

        if ($gs->force_update) {
            $clientVersion = session('app_version');

            // If no version in session, set it to current (first visit after system launch)
            if (!$clientVersion) {
                session(['app_version' => $gs->app_version]);
                return $next($request);
            }

            // If mismatch found, redirect to wall
            if (version_compare($clientVersion, $gs->app_version, '<')) {
                return redirect()->route('update.required');
            }
        }

        return $next($request);
    }
}
