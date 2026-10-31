<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\MarketPlace::select('id', 'slug', 'name', 'status')->limit(5)->get() as $m) {
    echo $m->id . ' | ' . $m->slug . ' | ' . $m->name . ' | status=' . $m->status . PHP_EOL;
}
