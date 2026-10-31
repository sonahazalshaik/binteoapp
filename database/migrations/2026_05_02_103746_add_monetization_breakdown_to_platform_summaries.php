<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('analytics_platform_summaries', function (Blueprint $table) {
            $table->decimal('revenue_ads', 15, 2)->default(0)->after('total_revenue');
            $table->decimal('revenue_subscriptions', 15, 2)->default(0)->after('revenue_ads');
            $table->decimal('revenue_purchases', 15, 2)->default(0)->after('revenue_subscriptions');
            $table->integer('signups_organic')->default(0)->after('new_users');
            $table->integer('signups_referral')->default(0)->after('signups_organic');
            $table->integer('signups_ads')->default(0)->after('signups_referral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_platform_summaries', function (Blueprint $table) {
            $table->dropColumn([
                'revenue_ads', 
                'revenue_subscriptions', 
                'revenue_purchases',
                'signups_organic',
                'signups_referral',
                'signups_ads'
            ]);
        });
    }
};
