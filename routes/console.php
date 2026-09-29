<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run analytics rollup every night at midnight
Schedule::command('analytics:aggregate')->dailyAt('00:00');

// Process queued jobs every minute (for cPanel/production where no persistent worker)
Schedule::command('queue:work --stop-when-empty --tries=3 --timeout=3600')
    ->everyMinute()
    ->withoutOverlapping();
