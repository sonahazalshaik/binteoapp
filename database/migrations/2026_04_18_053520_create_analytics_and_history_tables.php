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
        Schema::create('watch_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->integer('progress_seconds')->default(0);
            $table->timestamp('last_watched_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('platform_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('total_views')->default(0);
            $table->integer('total_watch_time')->default(0); // in minutes
            $table->integer('new_users')->default(0);
            $table->integer('new_videos')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_history');
        Schema::dropIfExists('platform_analytics');
    }
};
