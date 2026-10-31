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
        // 1. Enhance watch_history
        Schema::table('watch_history', function (Blueprint $table) {
            $table->integer('total_duration')->default(0)->after('progress_seconds');
            $table->boolean('is_completed')->default(false)->after('total_duration');
            $table->string('device_type')->nullable()->after('is_completed'); // mobile, desktop, tablet
        });

        // 2. Create video_events for granular tracking (buffering, quality changes, etc.)
        Schema::create('video_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // play, pause, buffer, seek, quality_change
            $table->string('value')->nullable(); // e.g., '1080p' for quality_change
            $table->integer('seconds_at')->default(0); // where in the video it happened
            $table->timestamps();
        });

        // 3. Create system_logs for tech metrics
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('route_name')->nullable();
            $table->string('url');
            $table->integer('response_time_ms')->default(0);
            $table->integer('status_code')->default(200);
            $table->boolean('is_error')->default(false);
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('watch_history', function (Blueprint $table) {
            $table->dropColumn(['total_duration', 'is_completed', 'device_type']);
        });
        Schema::dropIfExists('video_events');
        Schema::dropIfExists('system_logs');
    }
};
