<?php

namespace Database\Seeders;

use App\Models\Gateway;
use App\Models\GatewayCurrency;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Razorpay Gateway
        $gateway = Gateway::updateOrCreate(
            ['alias' => 'Razorpay'],
            [
                'name' => 'Razorpay',
                'status' => 1,
                'gateway_parameter' => [
                    'key_id' => 'rzp_test_vI8wI9wI9wI9wI',
                    'key_secret' => 'vI8wI9wI9wI9wIvI8wI9wI9wI'
                ]
            ]
        );

        // 2. Create Razorpay Currency (INR)
        GatewayCurrency::updateOrCreate(
            ['method_code' => 507, 'currency' => 'INR'],
            [
                'name' => 'Razorpay INR',
                'gateway_id' => $gateway->id,
                'min_amount' => 1.00,
                'max_amount' => 1000000.00,
                'percent_charge' => 0.00,
                'fixed_charge' => 0.00,
                'rate' => 1.00,
                'symbol' => '₹',
                'method_code' => 507,
                'gateway_parameter' => [
                    'key_id' => 'rzp_test_vI8wI9wI9wI9wI',
                    'key_secret' => 'vI8wI9wI9wI9wIvI8wI9wI9wI'
                ]
            ]
        );
    }
}
