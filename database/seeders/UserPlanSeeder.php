<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserPlan;

class UserPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing plans
        UserPlan::query()->delete();

        // Silver – 1 month
        UserPlan::create([
            'plan_name'       => 'Silver',
            'plan_price'      => 299,
            'plan_duration'   => 1,
            'plan_content'    => 'Custom Branding, Priority Listing, Unlimited Media',
            'is_featured_plan'=> false,
            'contact_access'  => true,
        ]);

        // Gold – 3 months
        UserPlan::create([
            'plan_name'       => 'Gold',
            'plan_price'      => 799,
            'plan_duration'   => 3,
            'plan_content'    => 'All Silver features plus extra benefits',
            'is_featured_plan'=> false,
            'contact_access'  => true,
        ]);

        // Platinum – 6 months
        UserPlan::create([
            'plan_name'       => 'Platinum',
            'plan_price'      => 1499,
            'plan_duration'   => 6,
            'plan_content'    => 'All Gold features plus premium support',
            'is_featured_plan'=> false,
            'contact_access'  => true,
        ]);

        // Diamond Pro – 12 months (featured)
        UserPlan::create([
            'plan_name'       => 'Diamond Pro',
            'plan_price'      => 2499,
            'plan_duration'   => 12,
            'plan_content'    => 'All Platinum features plus exclusive benefits',
            'is_featured_plan'=> true,
            'contact_access'  => true,
        ]);
    }
}
