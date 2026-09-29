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
        // Global Video Resolution Options
        Schema::create('video_processing_options', function (Blueprint $table) {
            $table->id();
            $table->string('resolution'); // 360p, 720p, 1080p, 4k
            $table->integer('bitrate')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        // Individual Processing Jobs
        Schema::create('video_processing_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->string('resolution');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_processing_jobs');
        Schema::dropIfExists('video_processing_options');
    }
};
