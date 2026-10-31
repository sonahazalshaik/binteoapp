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
        Schema::table('user_plans', function (Blueprint $table) {
            $table->boolean('is_featured_plan')->default(false)->after('plan_price');
        });

        Schema::table('market_places', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('status');
            $table->timestamp('featured_until')->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn('is_featured_plan');
        });

        Schema::table('market_places', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'featured_until']);
        });
    }
};
