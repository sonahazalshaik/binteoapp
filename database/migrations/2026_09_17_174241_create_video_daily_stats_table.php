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
        Schema::create('video_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id')->index();
            $table->date('date')->index();
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            // Ensure we don't have multiple rows for the same video on the same day
            $table->unique(['video_id', 'date']);
            
            // Note: Not adding foreign key constraint to video_id if we want soft-deletes or flexible schemas,
            // but standard is to cascade on delete:
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_daily_stats');
    }
};
