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
        // 1. Raw Event Tracking: Impressions
        if (!Schema::hasTable('impressions')) {
            Schema::create('impressions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('video_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id')->nullable()->index(); // To track unique viewers
                $table->string('source')->nullable()->index(); // e.g., 'search', 'browse', 'external', 'related'
                $table->timestamps();
            });
        }

        // 2. Advanced Aggregation: Demographic Stats
        if (!Schema::hasTable('demographic_stats')) {
            Schema::create('demographic_stats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('video_id')->constrained()->cascadeOnDelete();
                $table->date('date');
                $table->string('country', 2)->nullable()->index(); // ISO 2-letter country code
                $table->string('device_type', 50)->nullable()->index(); // mobile, desktop, tablet
                $table->string('age_group', 20)->nullable(); // e.g., '18-24', '25-34'
                $table->string('gender', 20)->nullable(); // 'male', 'female', 'other'
                $table->integer('views')->default(0);
                $table->timestamps();

                $table->unique(['video_id', 'date', 'country', 'device_type', 'age_group', 'gender'], 'demo_stats_unique');
            });
        }

        // 3. Enhance Existing Summary Tables
        Schema::table('analytics_content_summaries', function (Blueprint $table) {
            if (!Schema::hasColumn('analytics_content_summaries', 'impressions')) {
                $table->integer('impressions')->default(0)->after('daily_views');
            }
            if (!Schema::hasColumn('analytics_content_summaries', 'unique_viewers')) {
                $table->integer('unique_viewers')->default(0)->after('impressions');
            }
            if (!Schema::hasColumn('analytics_content_summaries', 'likes')) {
                $table->integer('likes')->default(0)->after('unique_viewers');
            }
            if (!Schema::hasColumn('analytics_content_summaries', 'comments')) {
                $table->integer('comments')->default(0)->after('likes');
            }
            if (!Schema::hasColumn('analytics_content_summaries', 'estimated_revenue')) {
                $table->decimal('estimated_revenue', 15, 4)->default(0)->after('comments');
            }
            if (!Schema::hasColumn('analytics_content_summaries', 'rpm')) {
                $table->decimal('rpm', 10, 4)->default(0)->after('estimated_revenue'); // Revenue per mille
            }
            if (!Schema::hasColumn('analytics_content_summaries', 'cpm')) {
                $table->decimal('cpm', 10, 4)->default(0)->after('rpm'); // Cost per mille
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analytics_content_summaries', function (Blueprint $table) {
            $table->dropColumn([
                'impressions', 
                'unique_viewers', 
                'likes', 
                'comments', 
                'estimated_revenue',
                'rpm',
                'cpm'
            ]);
        });

        Schema::dropIfExists('demographic_stats');
        Schema::dropIfExists('impressions');
    }
};
