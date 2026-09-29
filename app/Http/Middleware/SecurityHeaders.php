<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers for Architectural Fortification
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.plyr.io https://cdn.jsdelivr.net https://checkout.razorpay.com https://cdn.razorpay.com https://www.gstatic.com https://www.google-analytics.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.plyr.io https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; img-src 'self' data: blob: https:; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; connect-src 'self' https://vtp.bunny.net https://video.bunnycdn.com https://*.razorpay.com https://www.google-analytics.com https://cdn.jsdelivr.net https://www.gstatic.com https://*.b-cdn.net https://cdn.plyr.io https://firebaseinstallations.googleapis.com https://fcmregistrations.googleapis.com https://*.firebaseio.com ws://localhost:8080 wss://localhost:8080 ws://127.0.0.1:8080 wss://127.0.0.1:8080; frame-src 'self' https://*.bunny.net https://*.razorpay.com; media-src 'self' blob: https://*.bunny.net https://*.b-cdn.net; worker-src 'self' blob:;");

        // Force HSTS if on HTTPS and not in local environment
        if ($request->isSecure() && app()->environment() !== 'local') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
