<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Jobs\NotifySubscribers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

$outputFile = __DIR__ . '/verification_results.txt';
$fp = fopen($outputFile, 'w');

function logOut($str) {
    global $fp;
    fwrite($fp, $str);
    echo $str;
}

// Clean up any leaked test data from previous runs
DB::table('firebase_tokens')->where('token', 'like', 'fcm_token_ver_%')->delete();
DB::table('subscriptions')->where('user_id', '>=', 200000)->delete();
DB::table('users')->where('id', '>=', 200000)->delete();

function runActualVerification($subscriberCount) {
    logOut("========================================================\n");
    logOut("VERIFYING LOGIC FOR $subscriberCount SUBSCRIBERS\n");
    logOut("========================================================\n");
    
    // Explicitly delete users at the start of each run to prevent state leak
    DB::table('firebase_tokens')->where('token', 'like', 'fcm_token_ver_%')->delete();
    DB::table('subscriptions')->where('user_id', '>=', 200000)->delete();
    DB::table('users')->where('id', '>=', 200000)->delete();

    DB::beginTransaction();
    
    try {
        $user = \App\Models\User::first();
        if (!$user) {
            logOut("Error: Need at least one user in the database to run verification.\n");
            return;
        }
        
        $channel = $user->channel;
        if (!$channel) {
            $channel = \App\Models\Channel::create([
                'user_id' => $user->id,
                'name' => 'Verification Channel',
                'slug' => 'verification-channel-' . uniqid(),
            ]);
        }
        
        logOut("Creating mock subscribers and FCM tokens...\n");
        $usersToInsert = [];
        $subscribingUsers = [];
        $tokens = [];
        
        for ($i = 0; $i < $subscriberCount; $i++) {
            $userId = 200000 + $i;
            $usersToInsert[] = [
                'id' => $userId,
                'username' => 'ver_user_' . $userId,
                'name' => 'Verification User ' . $userId,
                'email' => 'ver_email_' . $userId . '@example.com',
                'password' => 'password',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $subscribingUsers[] = [
                'user_id' => $userId,
                'channel_id' => $channel->id,
                'notification_preference' => 'all',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $tokens[] = [
                'user_id' => $userId,
                'token' => 'fcm_token_ver_' . $userId . '_' . uniqid(),
                'device_type' => 'mobile',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach (array_chunk($usersToInsert, 1000) as $chunk) {
            DB::table('users')->insert($chunk);
        }
        foreach (array_chunk($subscribingUsers, 1000) as $chunk) {
            DB::table('subscriptions')->insert($chunk);
        }
        foreach (array_chunk($tokens, 1000) as $chunk) {
            DB::table('firebase_tokens')->insert($chunk);
        }
        
        logOut("Successfully created database sandbox entries.\n");
        
        $t1 = microtime(true);
        NotifySubscribers::dispatch(
            $user->id,
            $channel->name,
            'Verification Video Title',
            '/video/slug',
            'New Verification Alert',
            'video'
        );
        $t2 = microtime(true);
        logOut("T2 - T1 (Time to dispatch NotifySubscribers parent job): " . round(($t2 - $t1) * 1000, 2) . " ms\n");
        
        // Use DML delete instead of DDL truncate to avoid implicit commit in MySQL
        DB::table('jobs')->delete();
        
        $job = new NotifySubscribers(
            $user->id,
            $channel->name,
            'Verification Video Title',
            '/video/slug',
            'New Verification Alert',
            'video'
        );
        
        logOut("Executing NotifySubscribers synchronously to measure pipeline performance...\n");
        $t3 = microtime(true);
        $job->handle();
        $t4 = microtime(true);
        
        logOut("NotifySubscribers execution time: " . round(($t4 - $t3) * 1000, 2) . " ms\n");
        
        $jobCount = DB::table('jobs')->count();
        logOut("Jobs created in active queue: " . $jobCount . "\n");
        
    } catch (\Exception $e) {
        logOut("ERROR: " . $e->getMessage() . "\n");
    } finally {
        DB::rollBack();
        logOut("Database sandbox rollback completed successfully.\n");
    }
}

// Clear active jobs for clean reporting
DB::table('jobs')->delete();

runActualVerification(1);
runActualVerification(100);
runActualVerification(1000);
runActualVerification(10000);

fclose($fp);
