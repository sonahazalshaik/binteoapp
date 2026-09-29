<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnboardingSlide;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;
use Illuminate\Http\UploadedFile;

class OnboardingSlideSeeder extends Seeder
{
    public function run()
    {
        OnboardingSlide::truncate();

        $slides = [
            [
                'title' => 'Welcome to Binteo',
                'description' => 'Discover endless video content tailored just for you. Stream in high quality and join a community of creators and fans alike.',
                'image' => 'C:\\Users\\HP\\.gemini\\antigravity\\brain\\372f0286-622d-4504-84f5-1f897d428880\\slide_1_binteo_1783937211668.png',
                'sort_order' => 1
            ],
            [
                'title' => 'Discover Trending Creators',
                'description' => 'Find exactly what you love with our curated trending feeds. Connect with creators and never miss out on viral moments.',
                'image' => 'C:\\Users\\HP\\.gemini\\antigravity\\brain\\372f0286-622d-4504-84f5-1f897d428880\\slide_2_trending_1783937211723.png',
                'sort_order' => 2
            ],
            [
                'title' => 'Join the Community',
                'description' => 'Share your own videos, engage with audiences globally, and monetize your passion. The stage is yours to shine!',
                'image' => 'C:\\Users\\HP\\.gemini\\antigravity\\brain\\372f0286-622d-4504-84f5-1f897d428880\\slide_3_community_1783937221108.png',
                'sort_order' => 3
            ]
        ];

        foreach ($slides as $slide) {
            $imagePath = '';
            
            if (file_exists($slide['image'])) {
                try {
                    $uploadedFile = new UploadedFile(
                        $slide['image'],
                        basename($slide['image']),
                        mime_content_type($slide['image']),
                        null,
                        true // test mode
                    );
                    $imagePath = ImageHelper::uploadToR2($uploadedFile, 'onboarding');
                } catch (\Throwable $e) {
                    echo "R2 Upload failed for {$slide['title']}: " . $e->getMessage() . "\n";
                    // Fallback to public disk if R2 fails
                    $file = new \Illuminate\Http\File($slide['image']);
                    $imagePath = Storage::disk('public')->putFile('onboarding', $file);
                }
            } else {
                echo "File not found: " . $slide['image'] . "\n";
            }

            OnboardingSlide::create([
                'title' => $slide['title'],
                'description' => $slide['description'],
                'image_path' => $imagePath,
                'sort_order' => $slide['sort_order']
            ]);
        }
    }
}
