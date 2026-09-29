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
        Schema::create('analytics_daily_rollups', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('total_uploads')->default(0);
            $table->integer('mau')->default(0);
            $table->integer('avg_watch_time_seconds')->default(0);
            $table->integer('avg_session_duration_seconds')->default(0);
            $table->decimal('avg_vcr_percentage', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_daily_rollups');
    }
};
