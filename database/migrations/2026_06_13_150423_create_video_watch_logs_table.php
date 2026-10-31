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
        Schema::create('video_watch_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('video_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('reel_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_token')->index();
            $table->integer('watch_duration_seconds')->default(0);
            $table->decimal('completion_percentage', 5, 2)->default(0.00);
            $table->date('date')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_watch_logs');
    }
};
