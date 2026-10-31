<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;
use App\Constants\Status;

class StorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Storage::updateOrCreate(
            ['type' => Status::CLOUDFLARE_R2],
            [
                'name' => 'Cloudflare R2',
                'config' => [
                    'driver' => 's3',
                    'key' => env('R2_ACCESS_KEY', '6e99bbe575ec4aa2ff3ca96d007a3bc6'),
                    'secret' => env('R2_SECRET_KEY', '0c1f1ec27245b66c0819e5e5c89d7dbcfaed9aa469c6755ca7bfd34bf1f9b3c0'),
                    'bucket' => env('R2_BUCKET', 'youtube-clone'),
                    'region' => 'auto',
                    'endpoint' => env('R2_ENDPOINT', 'https://aa92945c6f72da312ba3b90f4960c9ed.r2.cloudflarestorage.com'),
                    'url' => env('R2_URL', 'https://aa92945c6f72da312ba3b90f4960c9ed.r2.cloudflarestorage.com/youtube-clone'),
                ],
                'status' => Status::ENABLE
            ]
        );

        $general = \App\Models\GeneralSetting::first();
        $general->cloudflare_config = [
            'access_key' => '6e99bbe575ec4aa2ff3ca96d007a3bc6',
            'secret_key' => '0c1f1ec27245b66c0819e5e5c89d7dbcfaed9aa469c6755ca7bfd34bf1f9b3c0',
            'bucket'     => 'youtube-clone',
            'endpoint'   => 'https://aa92945c6f72da312ba3b90f4960c9ed.r2.cloudflarestorage.com',
            'url'        => 'https://aa92945c6f72da312ba3b90f4960c9ed.r2.cloudflarestorage.com/youtube-clone'
        ];
        $general->razorpay_config = [
            'key'    => 'rzp_test_vP4Uvc02Llcnwb',
            'secret' => 'xHUMYRVnJhAHodfWAVYoR1sm',
        ];
        $general->save();
    }
}
