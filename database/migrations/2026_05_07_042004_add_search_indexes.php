<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds indexes to speed up search queries on videos, reels, channels, and copyright_strikes.
     * FULLTEXT indexes allow MySQL to use MATCH AGAINST (future) and still help with LIKE on some engines.
     * B-tree indexes on status/visibility/user_id columns speed up the WHERE filtering.
     */
    public function up(): void
    {
        // ── Videos table indexes ──
        Schema::table('videos', function (Blueprint $table) {
            // Composite index for the most common search filter combination
            $table->index(['status', 'visibility', 'user_id'], 'idx_videos_status_vis_user');
            // Index on user_id for JOIN performance (may already exist as FK)
            $table->index(['user_id', 'created_at'], 'idx_videos_user_created');
        });

        // FULLTEXT index for title/description search (MySQL 5.6+ InnoDB)
        try {
            DB::statement('ALTER TABLE videos ADD FULLTEXT INDEX idx_videos_search (title, description)');
        } catch (\Exception $e) {
            // Ignore if already exists or unsupported
        }

        // ── Reels table indexes ──
        Schema::table('reels', function (Blueprint $table) {
            $table->index(['status', 'user_id'], 'idx_reels_status_user');
            $table->index(['user_id', 'created_at'], 'idx_reels_user_created');
        });

        try {
            DB::statement('ALTER TABLE reels ADD FULLTEXT INDEX idx_reels_search (title, description)');
        } catch (\Exception $e) {
            // Ignore if already exists or unsupported
        }

        // ── Channels table indexes ──
        Schema::table('channels', function (Blueprint $table) {
            $table->index('user_id', 'idx_channels_user');
        });

        try {
            DB::statement('ALTER TABLE channels ADD FULLTEXT INDEX idx_channels_search (name, description)');
        } catch (\Exception $e) {
            // Ignore if already exists or unsupported
        }

        // ── Copyright strikes index (used in whereNotExists) ──
        Schema::table('copyright_strikes', function (Blueprint $table) {
            $table->index(['video_id', 'status'], 'idx_strikes_video_status');
            $table->index(['user_id', 'status'], 'idx_strikes_user_status');
        });

        // ── Users table (status used in JOINs) ──
        Schema::table('users', function (Blueprint $table) {
            $table->index('status', 'idx_users_status');
        });

        // ── Not interested videos (used in whereNotExists) ──
        if (Schema::hasTable('not_interested_videos')) {
            Schema::table('not_interested_videos', function (Blueprint $table) {
                $table->index(['video_id', 'user_id'], 'idx_ni_video_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropIndex('idx_videos_status_vis_user');
            $table->dropIndex('idx_videos_user_created');
        });

        try { DB::statement('ALTER TABLE videos DROP INDEX idx_videos_search'); } catch (\Exception $e) {}

        Schema::table('reels', function (Blueprint $table) {
            $table->dropIndex('idx_reels_status_user');
            $table->dropIndex('idx_reels_user_created');
        });

        try { DB::statement('ALTER TABLE reels DROP INDEX idx_reels_search'); } catch (\Exception $e) {}

        Schema::table('channels', function (Blueprint $table) {
            $table->dropIndex('idx_channels_user');
        });

        try { DB::statement('ALTER TABLE channels DROP INDEX idx_channels_search'); } catch (\Exception $e) {}

        Schema::table('copyright_strikes', function (Blueprint $table) {
            $table->dropIndex('idx_strikes_video_status');
            $table->dropIndex('idx_strikes_user_status');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_status');
        });

        if (Schema::hasTable('not_interested_videos')) {
            Schema::table('not_interested_videos', function (Blueprint $table) {
                $table->dropIndex('idx_ni_video_user');
            });
        }
    }
};
