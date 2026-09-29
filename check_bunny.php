<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$svc = app(\App\Services\BunnyStreamService::class);
$apiBase = config('bunny.api_base');
$libId = $svc->getLibraryId();
$apiKey = $svc->getApiKey();

$res = \Illuminate\Support\Facades\Http::withHeaders([
    'AccessKey' => $apiKey
])->get("$apiBase/library/$libId");

file_put_contents('bunny_lib.json', json_encode($res->json(), JSON_PRETTY_PRINT));
echo "Done";
