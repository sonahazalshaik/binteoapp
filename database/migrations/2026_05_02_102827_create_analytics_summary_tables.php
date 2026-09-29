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
        // Daily Platform Stats
        Schema::create('analytics_platform_summaries', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->date('recorded_at')->unique();
            $blueprint->integer('dau')->default(0);
            $blueprint->integer('mau')->default(0);
            $blueprint->integer('new_users')->default(0);
            $blueprint->integer('active_creators')->default(0);
            $blueprint->integer('videos_uploaded')->default(0);
            $blueprint->decimal('total_revenue', 15, 2)->default(0);
            $blueprint->integer('total_sessions')->default(0);
            $blueprint->float('avg_session_duration')->default(0);
            $blueprint->timestamps();
        });

        // Content Performance Stats
        Schema::create('analytics_content_summaries', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->unsignedBigInteger('video_id');
            $blueprint->date('recorded_at');
            $blueprint->integer('daily_views')->default(0);
            $blueprint->float('avg_watch_time')->default(0);
            $blueprint->float('completion_rate')->default(0);
            $blueprint->integer('rewatch_count')->default(0);
            $blueprint->timestamps();

            $blueprint->unique(['video_id', 'recorded_at']);
            $blueprint->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
        });

        // User Retention Stats (Cohorts)
        Schema::create('analytics_retention_summaries', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->date('cohort_date'); // Date user joined
            $blueprint->integer('day_n'); // 1, 7, 30
            $blueprint->integer('total_users')->default(0);
            $blueprint->integer('retained_users')->default(0);
            $blueprint->float('retention_rate')->default(0);
            $blueprint->timestamps();

            $blueprint->unique(['cohort_date', 'day_n']);
        });

        // Traffic Source Attribution
        Schema::create('analytics_traffic_summaries', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->date('recorded_at');
            $blueprint->string('source')->index(); // organic, referral, ads, etc.
            $blueprint->integer('visit_count')->default(0);
            $blueprint->integer('signup_count')->default(0);
            $blueprint->timestamps();

            $blueprint->unique(['recorded_at', 'source']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_traffic_summaries');
        Schema::dropIfExists('analytics_retention_summaries');
        Schema::dropIfExists('analytics_content_summaries');
        Schema::dropIfExists('analytics_platform_summaries');
    }
};
