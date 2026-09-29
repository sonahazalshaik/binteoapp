<?php

namespace Database\Seeders;

use App\Models\BannerAd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BannerAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing banners if needed
        // BannerAd::truncate();

        $slots = ['slot1', 'slot2', 'slot3', 'slot4'];
        
        $campaigns = [
            [
                'slot' => 'slot1',
                'image_name' => 'banner_tech_abstract',
                'link' => 'https://of2on.com/tech-promo',
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
            ],
            [
                'slot' => 'slot1',
                'image_name' => 'banner_cyberpunk_gaming',
                'link' => 'https://of2on.com/gaming-event',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addMonths(2),
            ],
            [
                'slot' => 'slot2',
                'image_name' => 'banner_nature_serene',
                'link' => 'https://of2on.com/nature-escapes',
                'start_date' => now(),
                'end_date' => now()->addWeeks(2),
            ],
            [
                'slot' => 'slot2',
                'image_name' => 'banner_premium_dark',
                'link' => 'https://of2on.com/luxury-collection',
                'start_date' => now()->addDays(2),
                'end_date' => now()->addMonths(1),
            ],
            [
                'slot' => 'slot3',
                'image_name' => 'banner_creative_vibrant',
                'link' => 'https://of2on.com/creative-arts',
                'start_date' => now(),
                'end_date' => now()->addDays(15),
            ],
            [
                'slot' => 'slot3',
                'image_name' => 'banner_minimalist_white',
                'link' => 'https://of2on.com/minimal-design',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addMonths(3),
            ],
            [
                'slot' => 'slot4',
                'image_name' => 'banner_corporate_blue',
                'link' => 'https://of2on.com/business-solutions',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
            ],
            [
                'slot' => 'slot4',
                'image_name' => 'banner_lifestyle_summer',
                'link' => 'https://of2on.com/summer-vibes',
                'start_date' => now()->addDays(20),
                'end_date' => now()->addMonths(2),
            ],
        ];

        foreach ($campaigns as $data) {
            BannerAd::create([
                'slot'       => $data['slot'],
                'image'      => 'banners/' . $data['slot'] . '/' . $data['image_name'] . '.png',
                'link'       => $data['link'],
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'status'     => true,
            ]);
        }
    }
}
