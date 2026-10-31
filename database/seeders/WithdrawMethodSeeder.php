<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WithdrawMethod;
use App\Constants\Status;

class WithdrawMethodSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            [
                'name' => 'Bank Transfer',
                'min_limit' => 500,
                'max_limit' => 100000,
                'fixed_charge' => 10,
                'percent_charge' => 1,
                'rate' => 1,
                'currency' => 'INR',
                'description' => 'Transfer directly to your bank account. Processed in 24-48 hours.',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Crypto (USDT)',
                'min_limit' => 2000,
                'max_limit' => 500000,
                'fixed_charge' => 50,
                'percent_charge' => 0,
                'rate' => 0.012,
                'currency' => 'USDT',
                'description' => 'Receive funds in your USDT (TRC20) wallet.',
                'status' => Status::ENABLE,
            ],
            [
                'name' => 'Google Pay / UPI',
                'min_limit' => 100,
                'max_limit' => 10000,
                'fixed_charge' => 0,
                'percent_charge' => 0,
                'rate' => 1,
                'currency' => 'INR',
                'description' => 'Instant transfer to your UPI ID.',
                'status' => Status::ENABLE,
            ],
        ];

        foreach ($methods as $method) {
            WithdrawMethod::create($method);
        }
    }
}
