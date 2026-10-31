<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CopyrightStrike;
use Illuminate\Support\Facades\Auth;

class CheckModerationStrikes
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
            $unreadStrike = CopyrightStrike::where('user_id', Auth::id())
                ->where('status', 'active')
                ->where('is_read', false)
                ->first();

            if ($unreadStrike) {
                // Flash the strike info to the session
                session()->flash('moderation_strike', [
                    'id' => $unreadStrike->id,
                    'reason' => $unreadStrike->reason,
                    'video_title' => $unreadStrike->video ? $unreadStrike->video->title : null,
                    'strike_count' => CopyrightStrike::where('user_id', Auth::id())->where('status', 'active')->count()
                ]);
            }
        }

        return $next($request);
    }
}
