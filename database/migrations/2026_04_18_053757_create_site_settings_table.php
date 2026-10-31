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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('New YouTube');
            $table->string('logo_path')->nullable();
            $table->bigInteger('max_upload_size')->default(104857600); // 100MB default
            $table->string('allowed_video_types')->default('mp4,mov,avi,wmv');
            $table->string('default_video_quality')->default('720p');
            $table->boolean('ads_enabled')->default(true);
            $table->boolean('monetization_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
