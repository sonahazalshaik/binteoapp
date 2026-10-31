<?php

namespace Database\Seeders;

use App\Models\AdminChannelPlan;
use Illuminate\Database\Seeder;

class AdminChannelPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => '1 Month Pass',
                'duration_type' => '1_month',
                'duration_days' => 30,
                'duration_months' => 1,
                'default_price' => 49.00,
                'description' => 'Access all exclusive premium videos from this channel for 1 month.',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '3 Months Pass',
                'duration_type' => '3_months',
                'duration_days' => 90,
                'duration_months' => 3,
                'default_price' => 129.00,
                'description' => 'Access all exclusive premium videos from this channel for 3 months with 12% savings.',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => '6 Months Pass',
                'duration_type' => '6_months',
                'duration_days' => 180,
                'duration_months' => 6,
                'default_price' => 249.00,
                'description' => 'Access all exclusive premium videos from this channel for 6 months with 15% savings.',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => '1 Year VIP Pass',
                'duration_type' => '1_year',
                'duration_days' => 365,
                'duration_months' => 12,
                'default_price' => 449.00,
                'description' => 'Full 1-year unlimited VIP access to all premium videos with maximum 25% savings.',
                'status' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            AdminChannelPlan::updateOrCreate(
                ['duration_type' => $plan['duration_type']],
                $plan
            );
        }
    }
}
