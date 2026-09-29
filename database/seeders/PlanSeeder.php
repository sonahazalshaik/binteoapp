<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Silver Plan - 1 month
        Plan::updateOrCreate(
            ['name' => 'Silver'],
            [
                'price' => 299,
                'duration' => 30,
                'description' => 'Custom Branding, Priority Listing, Unlimited Media',
                'video_access' => 1,
                'status' => 1
            ]
        );

        // Gold Plan - 3 months
        Plan::updateOrCreate(
            ['name' => 'Gold'],
            [
                'price' => 799,
                'duration' => 90,
                'description' => 'All Silver features plus extra benefits',
                'video_access' => 1,
                'status' => 1
            ]
        );

        // Platinum Plan - 6 months
        Plan::updateOrCreate(
            ['name' => 'Platinum'],
            [
                'price' => 1499,
                'duration' => 180,
                'description' => 'All Gold features plus premium support',
                'video_access' => 1,
                'status' => 1
            ]
        );

        // Diamond Pro - 12 months
        Plan::updateOrCreate(
            ['name' => 'Diamond Pro'],
            [
                'price' => 2499,
                'duration' => 365,
                'description' => 'All Platinum features plus exclusive benefits',
                'video_access' => 1,
                'status' => 1
            ]
        );
    }
}
