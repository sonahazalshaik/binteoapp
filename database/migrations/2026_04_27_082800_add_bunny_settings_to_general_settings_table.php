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
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('bunny_api_key')->nullable();
            $table->string('bunny_video_library_id')->nullable();
            $table->string('bunny_reel_library_id')->nullable();
            $table->string('bunny_video_collection_id')->nullable();
            $table->string('bunny_reel_collection_id')->nullable();
            $table->string('bunny_cdn_hostname')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'bunny_api_key',
                'bunny_video_library_id',
                'bunny_reel_library_id',
                'bunny_video_collection_id',
                'bunny_reel_collection_id',
                'bunny_cdn_hostname'
            ]);
        });
    }
};
