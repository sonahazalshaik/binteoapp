<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BunnySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $general = \App\Models\GeneralSetting::first();
        if ($general) {
            $general->update([
                'bunny_reels_storage_zone'       => 'binteoreels-storage',
                'bunny_reels_storage_access_key' => '0c4aecc8-ff0d-4152-a66dcfb1bf1a-96a3-4c75',
                'bunny_reels_storage_region'     => 'de',
                'bunny_reels_pull_zone'          => 'reelscdn.b-cdn.net',
                'reels_max_upload_size'          => 100,
                'reels_duration_limit'           => 60,
                'reels_compression_size'         => 15,
            ]);
        }
    }
}
