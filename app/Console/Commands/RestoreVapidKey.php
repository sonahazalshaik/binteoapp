<?php

namespace App\Console\Commands;

use App\Models\GeneralSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RestoreVapidKey extends Command
{
    protected $signature = 'firebase:restore-vapid';
    protected $description = 'Restore missing vapidKey in firebase_config from .env';

    public function handle()
    {
        $general = GeneralSetting::first();

        if (!$general) {
            $this->error('No general settings found.');
            return;
        }

        $config = $general->firebase_config ? (array) $general->firebase_config : [];

        if (!empty($config['vapidKey'])) {
            $this->info('vapidKey already present in firebase_config.');
            return;
        }

        $vapidKey = config('services.firebase.vapid_key');

        if (!$vapidKey) {
            $this->error('FIREBASE_VAPID_KEY not found in .env');
            return;
        }

        $config['vapidKey'] = $vapidKey;

        $general->firebase_config = $config;
        $general->save();

        Cache::forget('GeneralSetting');

        $this->info('vapidKey restored successfully to firebase_config.');
    }
}