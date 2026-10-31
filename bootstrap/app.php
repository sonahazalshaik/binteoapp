<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'no_admin' => \App\Http\Middleware\BlockAdminFromFrontend::class,
            'xss' => \App\Http\Middleware\XssSanitization::class,
            'security_headers' => \App\Http\Middleware\SecurityHeaders::class,
            'throttle_views' => \App\Http\Middleware\ThrottleVideoViews::class,
            'honeypot' => \App\Http\Middleware\Honeypot::class,
        ]);

        // Exclude external webhook/IPN endpoints, TUS proxy, and Telemetry from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'ipn/*',
            'tus-proxy',
            'tus-proxy/*',
            'api/telemetry/*',
        ]);


        $middleware->trustProxies(at: '*');

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\IpBlockingMiddleware::class,
            \App\Http\Middleware\VisitorLogMiddleware::class,
            \App\Http\Middleware\ForceUpdateMiddleware::class,
            \App\Http\Middleware\BlockAdminFromFrontend::class,
            \App\Http\Middleware\XssSanitization::class,
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\Honeypot::class,
            \App\Http\Middleware\CheckUserStatus::class,
            \App\Http\Middleware\CaptureTrafficSource::class,
            \App\Http\Middleware\LogSystemPerformance::class,
            \App\Http\Middleware\CheckModerationStrikes::class,
            \App\Http\Middleware\CheckModerationNotices::class,
            \App\Http\Middleware\UpdateLastSeen::class,
            \App\Http\Middleware\ProcessQueuedJobs::class,
        ]);

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                if (Auth::guard('admin')->check()) {
                    return route('admin.dashboard');
                }
                return null;
            }
            if (Auth::check()) {
                return route('studio.dashboard');
            }
        });

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('analytics:aggregate')->dailyAt('03:00');
        $schedule->command('reel:cleanup-orphan-temps')->weekly()->sundays()->at('04:00');
    })->create();
