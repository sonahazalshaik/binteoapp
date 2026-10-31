<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SiteSetting::create([
            'site_name' => 'of2on tube',
            'max_upload_size' => 524288000, // 500MB
            'allowed_video_types' => 'mp4,mov,avi,wmv',
            'default_video_quality' => '1080p',
            'ads_enabled' => true,
            'monetization_enabled' => true
        ]);
    }
}
