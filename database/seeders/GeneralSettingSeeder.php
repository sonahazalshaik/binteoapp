<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Cache;

class GeneralSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = GeneralSetting::first();

        if (!$settings) {
            GeneralSetting::create([
                'site_name' => 'of2on tube',
                'cur_sym' => '$',
                'cur_text' => 'USD',
                'base_color' => 'EA001F',
                'secondary_color' => '000000',
                'registration' => 1,
                'ev' => 0,
                'en' => 0,
                'sv' => 0,
                'sn' => 0,
                'kv' => 0,
                'force_ssl' => 0,
                'secure_password' => 0,
                'agree' => 0,
                'multi_language' => 0,
                'is_storage' => 1,
                'is_playlist_sell' => 1,
                'is_monthly_subscription' => 1,
                'ffmpeg_status' => 0,
                'ads_auto_approve' => 1,
                'ads_module' => 1,
                'monetization_amount' => 0,
                'monetization_status' => 0,
            ]);
        }

        Cache::forget('GeneralSetting');
    }
}
