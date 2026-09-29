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
        if (!Schema::hasTable('campaigns')) {
            Schema::create('campaigns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('clients')->onDelete('cascade');
                $table->string('name');
                $table->decimal('available_amount', 28, 8)->default(0);
                $table->decimal('total_budget', 28, 8)->default(0);
                $table->tinyInteger('payment_status')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('advertisement_analytics')) {
            Schema::create('advertisement_analytics', function (Blueprint $table) {
                $table->id();
                $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
                $table->tinyInteger('click')->default(0);
                $table->tinyInteger('impression')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('advertisement_reaches')) {
            Schema::create('advertisement_reaches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
                $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
                $table->string('user_ip', 45);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('advertisement_countries')) {
            Schema::create('advertisement_countries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
                $table->string('country');
                $table->tinyInteger('except')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('advertisement_schedules')) {
            Schema::create('advertisement_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('advertisement_id')->constrained()->onDelete('cascade');
                $table->date('custom_start_date')->nullable();
                $table->date('custom_end_date')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('advertisements', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisements', 'daily_costs')) $table->decimal('daily_costs', 28, 8)->default(0);
            if (!Schema::hasColumn('advertisements', 'ad_reached')) $table->integer('ad_reached')->default(0);
            if (!Schema::hasColumn('advertisements', 'ad_engagement')) $table->integer('ad_engagement')->default(0);
            if (!Schema::hasColumn('advertisements', 'impression')) $table->integer('impression')->default(0);
            if (!Schema::hasColumn('advertisements', 'is_all_countries')) $table->tinyInteger('is_all_countries')->default(1);
            if (!Schema::hasColumn('advertisements', 'is_all_categories')) $table->tinyInteger('is_all_categories')->default(1);
            if (!Schema::hasColumn('advertisements', 'schedule_type')) $table->tinyInteger('schedule_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn([
                'daily_costs', 'ad_reached', 'ad_engagement', 'impression',
                'is_all_countries', 'is_all_categories', 'schedule_type'
            ]);
        });
        Schema::dropIfExists('advertisement_schedules');
        Schema::dropIfExists('advertisement_countries');
        Schema::dropIfExists('advertisement_reaches');
        Schema::dropIfExists('advertisement_analytics');
        Schema::dropIfExists('campaigns');
    }
};
