<?php
// Place this in your public/ folder on the live server and open it in your browser.

define('LARAVEL_START', microtime(true));

// Load Laravel Bootstrap
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain');

echo "=============================================\n";
echo "       Binteo Live DB Diagnostic Tool        \n";
echo "=============================================\n\n";

// 1. Check Database connection details
try {
    $dbName = DB::connection()->getDatabaseName();
    echo "Connected successfully to database: {$dbName}\n\n";
} catch (\Exception $e) {
    echo "ERROR: Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// 2. Search for specific GUID if provided via URL query parameter ?search=9f312595-...
$searchGuid = $_GET['search'] ?? null;
if ($searchGuid) {
    echo "Searching for Bunny ID: {$searchGuid}\n";
    
    $reel = DB::table('reels')->where('bunny_id', $searchGuid)->first();
    if ($reel) {
        echo "[FOUND] In reels table! Reel ID: {$reel->id} | Title: {$reel->title} | Status: {$reel->status}\n";
    } else {
        echo "[NOT FOUND] In reels table.\n";
    }

    $video = DB::table('videos')->where('bunny_id', $searchGuid)->first();
    if ($video) {
        echo "[FOUND] In videos table! Video ID: {$video->id} | Title: {$video->title}\n";
    } else {
        echo "[NOT FOUND] In videos table.\n";
    }
    echo "\n";
} else {
    echo "TIP: You can search for a specific GUID by visiting: ?search=YOUR_GUID_HERE\n\n";
}

// 3. List last 10 reels in the database
echo "Latest 10 Reels in Live Database:\n";
echo str_pad("ID", 6) . " | " . str_pad("Status", 10) . " | " . str_pad("Bunny ID", 38) . " | Title\n";
echo str_repeat("-", 80) . "\n";

$reels = DB::table('reels')->latest()->limit(10)->get();
foreach ($reels as $r) {
    echo str_pad($r->id, 6) . " | " . 
         str_pad($r->status, 10) . " | " . 
         str_pad($r->bunny_id ?? 'NULL', 38) . " | " . 
         $r->title . "\n";
}

echo "\n=============================================\n";
