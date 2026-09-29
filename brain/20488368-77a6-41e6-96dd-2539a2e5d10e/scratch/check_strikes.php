<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\CopyrightStrike;

$email = 'sonahazal.sbspl@gmail.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found: $email\n";
    exit;
}

$strikes = CopyrightStrike::where('user_id', $user->id)->get();

echo "User: " . $user->username . " (ID: " . $user->id . ")\n";
echo "Total Strikes: " . $strikes->count() . "\n";
foreach ($strikes as $strike) {
    echo " - ID: " . $strike->id . ", Status: " . $strike->status . ", Is Read: " . ($strike->is_read ? 'Yes' : 'No') . ", Reason: " . $strike->reason . "\n";
}
