<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class Honeypot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip honeypot check for TUS proxy routes — $request->filled()
        // triggers input parsing which consumes binary upload bodies
        if ($request->is('tus-proxy', 'tus-proxy/*')) {
            return $next($request);
        }

        // If the hidden 'my_name_hp' field is filled, it's likely a bot
        if ($request->filled('my_name_hp')) {
            Log::warning('Honeypot Trap: Bot submission blocked', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'input' => $request->except(['_token', 'password', 'password_confirmation'])
            ]);
            
            // Silently fail or abort. Bots should think it worked or get a 403.
            return abort(403, 'Unauthorized bot activity detected.');
        }

        return $next($request);
    }
}
