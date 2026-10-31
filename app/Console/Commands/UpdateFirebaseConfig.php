<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateFirebaseConfig extends Command
{
    protected $signature = 'firebase:update-config';
    protected $description = 'Update firebase configuration in general_settings table';

    public function handle()
    {
        $jsonFile = base_path('matrimony-v1-ed605-firebase-adminsdk-fbsvc-540b9829e8.json');
        
        if (!file_exists($jsonFile)) {
            $this->error('Firebase JSON file not found.');
            return;
        }

        $config = json_decode(file_get_contents($jsonFile), true);
        
        $firebase_config = [
            'apiKey' => 'AIzaSyBhllyvVspP7lXIhQrYmLwZZQvT6tiRpw8',
            'authDomain' => 'matrimony-v1-ed605.firebaseapp.com',
            'projectId' => 'matrimony-v1-ed605',
            'storageBucket' => 'matrimony-v1-ed605.firebasestorage.app',
            'messagingSenderId' => '328237735040',
            'appId' => '1:328237735040:web:0531c01f764c3b524e9a65',
            'measurementId' => 'G-BQPL9PMCS2',
            'vapidKey' => 'BAtK-MBza52QvkBWtv_IdDnXnK9HD8F0OnzuTWVGWhw3Qg998hM__zQosX0i-ixDZgZBhjeauZ4En-8Azqi1lhc'
        ];

        DB::table('general_settings')->update([
            'firebase_config' => json_encode($firebase_config)
        ]);

        $this->info('Firebase configuration updated successfully.');
    }
}
