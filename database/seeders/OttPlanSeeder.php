<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OttPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Mini OTT BASIC',
                'price' => 199,
                'description' => 'Essential access to all premium movies and shows for 30 days.',
                'duration' => 30,
                'ott_access' => true,
                'status' => true
            ],
            [
                'name' => 'Mini OTT PRO',
                'price' => 499,
                'description' => 'Extended premium experience with early access to new releases for 90 days.',
                'duration' => 90,
                'ott_access' => true,
                'status' => true
            ],
            [
                'name' => 'Mini OTT ELITE',
                'price' => 1499,
                'description' => 'The ultimate annual pass. 365 days of unlimited premium cinematic content.',
                'duration' => 365,
                'ott_access' => true,
                'status' => true
            ]
        ];

        foreach ($plans as $plan) {
            \App\Models\OttPlan::create($plan);
        }
    }
}
