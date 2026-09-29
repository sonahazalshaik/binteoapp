<?php

namespace Database\Seeders;

use App\Models\MarketPlace;
use App\Models\MarketSubscription;
use App\Models\UserPlan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FeaturedPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Find the existing Platinum featured plan
        $plan = UserPlan::where('plan_name', 'Platinum Plan')->first();

        if (!$plan) {
            // Fallback to any featured plan if Platinum is missing
            $plan = UserPlan::where('is_featured_plan', true)->first();
        }

        if (!$plan) {
            // Last resort: Create it
            $plan = UserPlan::create([
                'plan_name' => 'Platinum Plan',
                'plan_price' => 500.00,
                'plan_duration' => 365,
                'plan_content' => 'Premium featured access.',
                'is_featured_plan' => true,
                'contact_access' => true,
            ]);
        }

        // 2. Assign to first 10 active creators
        $creators = MarketPlace::where('status', 1)->take(10)->get();

        foreach ($creators as $creator) {
            // Create subscription
            MarketSubscription::updateOrCreate(
                ['marketplace_id' => $creator->id, 'plan_id' => $plan->id],
                [
                    'plan_name' => $plan->plan_name,
                    'plan_price' => $plan->plan_price,
                    'plan_duration' => $plan->plan_duration,
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addDays($plan->plan_duration),
                ]
            );

            // Also update the is_featured column for redundancy/compatibility
            $creator->is_featured = true;
            $creator->save();
        }

        echo "Successfully seeded featured plans for 5 creators.\n";
    }
}
