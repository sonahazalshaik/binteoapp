<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constants\Status;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status == Status::USER_BAN) {
                $reason = $user->ban_reason ?? 'Your account has been restricted by the administrator.';
                
                Auth::guard('web')->logout();
                $request->session()->forget(Auth::guard('web')->getName());
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with([
                    'ban_modal' => true,
                    'ban_reason' => $reason
                ]);
            }

            // Check for pending deletion request
            $deletionRequest = $user->deletionRequest()->where('status', 'pending')->first();
            if ($deletionRequest) {
                Auth::guard('web')->logout();
                $request->session()->forget(Auth::guard('web')->getName());
                $request->session()->regenerateToken();

                return redirect()->route('login')->with([
                    'deletion_pending' => true,
                    'deletion_reason' => $deletionRequest->reason . ($deletionRequest->custom_reason ? ' (' . $deletionRequest->custom_reason . ')' : '')
                ]);
            }
        }

        return $next($request);
    }
}
