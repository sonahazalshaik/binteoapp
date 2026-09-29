<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
 
class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();
            
            // Only update once every 5 minutes to reduce DB load
            if (!$user->last_seen || $user->last_seen->addMinutes(5)->isPast()) {
                $user->last_seen = now();
                $user->save();
            }
 
            // Also update Cache for real-time online status
            Cache::put('user-is-online-' . $user->id, true, now()->addMinutes(5));
        }
 
        return $next($request);
    }
}
