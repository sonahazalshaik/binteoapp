<?php

namespace App\Providers;

use App\Models\Category;
use App\Constants\Status;
use Illuminate\Support\ServiceProvider;

use Illuminate\Foundation\AliasLoader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inject Configs from General Settings
        try {
            if ($this->hasTable('general_settings')) {
                $general = gs();
                \Illuminate\Support\Facades\View::share('general', $general);
                
                if ($general) {
                    // Cloudflare R2
                    if (isset($general->cloudflare_config)) {
                        $config = $general->cloudflare_config;
                        config([
                            'filesystems.disks.r2.key'      => $config->access_key ?? config('filesystems.disks.r2.key'),
                            'filesystems.disks.r2.secret'   => $config->secret_key ?? config('filesystems.disks.r2.secret'),
                            'filesystems.disks.r2.bucket'   => $config->bucket     ?? config('filesystems.disks.r2.bucket'),
                            'filesystems.disks.r2.endpoint' => $config->endpoint   ?? config('filesystems.disks.r2.endpoint'),
                            'filesystems.disks.r2.url'      => $config->url        ?? config('filesystems.disks.r2.url'),
                        ]);
                    }

                    // Razorpay
                    if (isset($general->razorpay_config)) {
                        $config = $general->razorpay_config;
                        config([
                            'services.razorpay.key'    => $config->key    ?? config('services.razorpay.key'),
                            'services.razorpay.secret' => $config->secret ?? config('services.razorpay.secret'),
                        ]);
                    }

                    // Google OAuth
                    if ($general->google_client_id) {
                        config([
                            'services.google.client_id'     => $general->google_client_id,
                            'services.google.client_secret' => $general->google_client_secret,
                            'services.google.redirect'      => $general->google_redirect_uri,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail if DB not connected
        }

        $loader = AliasLoader::getInstance();
        $loader->alias('Status', Status::class);

        if (request()->header('X-Forwarded-Proto') === 'https' || (app()->environment() !== 'local' && str_contains(config('app.url'), 'https://'))) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Database\Eloquent\Builder::macro('searchable', (new \App\Lib\Searchable)->searchable());
        \Illuminate\Database\Eloquent\Builder::macro('filter', (new \App\Lib\Searchable)->filter());
        \Illuminate\Database\Eloquent\Builder::macro('dateFilter', (new \App\Lib\Searchable)->dateFilter());
        \Illuminate\Database\Eloquent\Builder::macro('sortFilter', (new \App\Lib\Searchable)->sortFilter());

        if (!app()->runningInConsole()) {
            try {
                if ($this->hasTable('site_settings')) {
                    $siteSettings = \App\Models\SiteSetting::first() ?? new \App\Models\SiteSetting([
                        'site_name' => 'of2on tube',
                        'ads_enabled' => true,
                        'monetization_enabled' => true
                    ]);
                    \Illuminate\Support\Facades\View::share('siteSettings', $siteSettings);
                    \Illuminate\Support\Facades\View::share('emptyMessage', 'No data found');
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Boot: site_settings share skipped: ' . $e->getMessage());
            }

            try {
                if ($this->hasTable('categories')) {
                    \Illuminate\Support\Facades\View::share('categories', Category::active()->orderByRaw("CASE WHEN name = 'Entertainment' THEN 0 ELSE 1 END")->orderBy('name')->get());
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Boot: categories share skipped: ' . $e->getMessage());
                \Illuminate\Support\Facades\View::share('categories', collect());
            }

            if (request()->is('admin*')) {
                try {
                    if ($this->hasTable('users')) {
                        \Illuminate\Support\Facades\View::share('users', \App\Models\User::active()->orderBy('username')->get());
                    }
                    if ($this->hasTable('market_places')) {
                        \Illuminate\Support\Facades\View::share('talents', \App\Models\MarketPlace::orderBy('id', 'desc')->get());
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Boot: admin shares skipped: ' . $e->getMessage());
                }

                \Illuminate\Support\Facades\View::composer('admin.layouts.app', function ($view) {
                    $sidenavPath = resource_path('views/admin/partials/sidenav.json');
                    $sideBarLinks = [];
                    if (file_exists($sidenavPath)) {
                        $sideBarLinks = json_decode(file_get_contents($sidenavPath));
                    }

                    $view->with([
                        'sideBarLinks'               => $sideBarLinks,
                        'bannedUsersCount'           => \App\Models\User::banned()->count(),
                        'emailUnverifiedUsersCount'  => \App\Models\User::emailUnverified()->count(),
                        'mobileUnverifiedUsersCount' => \App\Models\User::mobileUnverified()->count(),
                        'kycUnverifiedUsersCount'    => \App\Models\User::kycUnverified()->count(),
                        'kycPendingUsersCount'       => \App\Models\User::kycPending()->count(),
                        'pendingDeletionCount'       => \App\Models\DeletionRequest::where('status', 'pending')->count(),
                        'pendingTicketCount'         => \App\Models\SupportTicket::whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                        'pendingDepositsCount'       => \App\Models\Deposit::pending()->count(),
                        'pendingWithdrawCount'       => \App\Models\Withdrawal::pending()->count(),
                        'pendingAdvertiserCount'     => \App\Models\User::pendingAdvertisers()->count(),
                        'monetizationRequestCount'   => \App\Models\User::monetizationRequest()->count(),
                        'pendingAdsCount'            => \App\Models\Advertisement::pending()->count(),
                        'updateAvailable'            => 0,
                        'adminNotificationCount'     => \App\Models\AdminNotification::where('is_read', Status::NO)->count(),
                        'adminNotifications'          => \App\Models\AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                    ]);
                });
            }
        }
        $this->configureRateLimiting();
    }

    /**
     * Optimized Table Existence Check
     */
    private function hasTable($table)
    {
        static $tables = [];
        if (!isset($tables[$table])) {
            $tables[$table] = \Illuminate\Support\Facades\Cache::remember("table_exists_{$table}", 86400, function () use ($table) {
                return \Illuminate\Support\Facades\Schema::hasTable($table);
            });
        }
        return $tables[$table];
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        \Illuminate\Support\Facades\RateLimiter::for('global', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(1000)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('search', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(30)->by($request->ip())->response(function () {
                return response()->json(['message' => 'Too many search requests. Please slow down.'], 429);
            });
        });

        \Illuminate\Support\Facades\RateLimiter::for('auth', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('upload', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perHour(100)->by($request->user()?->id ?: $request->ip());
        });
    }
}
