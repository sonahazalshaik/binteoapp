<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class BlockAdminFromFrontend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
        /*
        // Skip this middleware for admin routes
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        // Check if the user is logged in via the 'web' guard and has the admin role
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'admin') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->withNotify([['error', 'Admins are restricted from accessing frontend templates.']]);
        }

        // Also check if they are logged in via 'admin' guard and trying to access frontend
        // If you want to block them even if they are ONLY logged into the admin panel
        // We only block if they are ONLY logged in as admin and trying to access frontend
        // OR if they are logged into web guard with an admin role.
        if (Auth::guard('admin')->check() && !Auth::guard('web')->check() && !$request->is('admin*')) {
             return redirect()->route('admin.dashboard')->withNotify([['warning', 'Accessing frontend as an Admin is restricted. Please login as a user.']]);
        }

        return $next($request);
        */
    }
}
