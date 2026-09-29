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
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'per_click_spent')) {
                $table->decimal('per_click_spent', 28, 8)->default(0);
            }
            if (!Schema::hasColumn('general_settings', 'per_impression_spent')) {
                $table->decimal('per_impression_spent', 28, 8)->default(0);
            }
            if (!Schema::hasColumn('general_settings', 'per_click_earn')) {
                $table->decimal('per_click_earn', 28, 8)->default(0);
            }
            if (!Schema::hasColumn('general_settings', 'per_impression_earn')) {
                $table->decimal('per_impression_earn', 28, 8)->default(0);
            }
            if (!Schema::hasColumn('general_settings', 'ads_module')) {
                $table->tinyInteger('ads_module')->default(0);
            }
            if (!Schema::hasColumn('general_settings', 'ad_reach')) {
                $table->decimal('ad_reach', 28, 8)->default(0);
            }
            if (!Schema::hasColumn('general_settings', 'ad_engagement')) {
                $table->decimal('ad_engagement', 28, 8)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'per_click_spent', 'per_impression_spent', 'per_click_earn', 
                'per_impression_earn', 'ads_module', 'ad_reach', 'ad_engagement'
            ]);
        });
    }
};
