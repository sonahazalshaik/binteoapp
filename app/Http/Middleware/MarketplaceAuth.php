<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MarketplaceAuth
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
        if (!Session::has('marketplace_user_id')) {
            return redirect()->route('marketplace.login')->with('error', 'Authentication required for fleet access.');
        }

        $userId = Session::get('marketplace_user_id');
        $user = \App\Models\MarketPlace::find($userId);
        if ($user) {
            $deletionRequest = $user->deletionRequest()->where('status', 'pending')->first();
            if ($deletionRequest) {
                Session::forget('marketplace_user_id');
                return redirect()->route('marketplace.login')->with([
                    'deletion_pending' => true,
                    'deletion_reason' => $deletionRequest->reason . ($deletionRequest->custom_reason ? ' (' . $deletionRequest->custom_reason . ')' : '')
                ]);
            }
        }

        return $next($request);
    }
}
