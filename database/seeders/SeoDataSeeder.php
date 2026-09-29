<?php

namespace Database\Seeders;

use App\Models\Frontend;
use Illuminate\Database\Seeder;

class SeoDataSeeder extends Seeder
{
    public function run(): void
    {
        $seo = Frontend::firstOrCreate(['data_keys' => 'seo.data']);

        $data = (array) ($seo->data_values ?? []);

        $defaults = [
            'keywords' => [
                'video sharing',
                'watch videos online',
                'upload video',
                'short videos',
                'trending reels',
                'viral videos',
                'free video platform',
            ],
            'description' => 'Watch, upload and share trending videos, shorts and reels. Join our creator community and discover fresh content every day.',
            'social_title' => 'Watch & Share Trending Videos',
            'social_description' => 'Discover trending videos and reels, or upload your own and grow your audience.',
            'image' => $data['image'] ?? null,
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($data[$key]) || $data[$key] === '' || $data[$key] === [] || $data[$key] === null) {
                $data[$key] = $value;
            }
        }

        $seo->data_values = $data;
        $seo->save();

        $this->command->info('SEO data seeded: keywords=' . count($data['keywords']));
    }
}
