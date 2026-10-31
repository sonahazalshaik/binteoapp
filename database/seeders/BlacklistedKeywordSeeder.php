<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlacklistedKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keywords = [
            'spam', 'scam', 'abuse', 'hate', 'stupid', 'idiot', 'fake', 
            'offensive', 'garbage', 'trash', 'useless', 'terrible'
        ];

        foreach ($keywords as $word) {
            \App\Models\BlacklistedKeyword::firstOrCreate(['keyword' => $word]);
        }
    }
}
