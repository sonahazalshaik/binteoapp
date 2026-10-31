<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureTrafficSource
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for TUS proxy routes — $request->has()/$request->get()
        // trigger input parsing which can consume binary upload bodies
        if ($request->is('tus-proxy', 'tus-proxy/*')) {
            return $next($request);
        }

        // Capture UTM parameters and store them in session if not already set
        $utmParams = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        
        foreach ($utmParams as $param) {
            if ($request->has($param)) {
                session([$param => $request->get($param)]);
            }
        }

        // Capture referrer if it's external
        if ($request->header('referer')) {
            $referrerHost = parse_url($request->header('referer'), PHP_URL_HOST);
            $currentHost = $request->getHost();
            
            if ($referrerHost && $referrerHost !== $currentHost && !session()->has('referral_url')) {
                session(['referral_url' => $request->header('referer')]);
                
                // Categorize basic traffic source
                if (!session()->has('traffic_source')) {
                    $source = 'referral';
                    if (preg_match('/(google|bing|yahoo|duckduckgo)/i', $referrerHost)) {
                        $source = 'organic';
                    } elseif (preg_match('/(facebook|instagram|twitter|linkedin|tiktok|youtube)/i', $referrerHost)) {
                        $source = 'social';
                    }
                    session(['traffic_source' => $source]);
                }
            }
        }

        // Default to organic if no referrer and it's the first visit
        if (!session()->has('traffic_source') && !$request->header('referer')) {
             // If they have UTM source, it's probably 'ads' or 'social'
             if ($request->has('utm_source')) {
                 $source = 'ads'; // Default UTM-based landing to ads if not social
                 if (preg_match('/(facebook|social|twitter|instagram)/i', $request->get('utm_source'))) {
                     $source = 'social';
                 }
                 session(['traffic_source' => $source]);
             } else {
                 session(['traffic_source' => 'organic']);
             }
        }

        return $next($request);
    }
}
