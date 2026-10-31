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
        Schema::table('reels', function (Blueprint $table) {
            $table->string('storage_path')->nullable()->index()->after('compressed_video_path');
        });

        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('bunny_reels_storage_zone')->nullable();
            $table->string('bunny_reels_storage_access_key')->nullable();
            $table->string('bunny_reels_storage_region')->nullable();
            $table->string('bunny_reels_pull_zone')->nullable();
            $table->integer('reels_duration_limit')->default(60);
            $table->integer('reels_compression_size')->default(15);
            $table->integer('reels_max_upload_size')->default(100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reels', function (Blueprint $table) {
            $table->dropColumn('storage_path');
        });

        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'bunny_reels_storage_zone',
                'bunny_reels_storage_access_key',
                'bunny_reels_storage_region',
                'bunny_reels_pull_zone',
                'reels_duration_limit',
                'reels_compression_size',
                'reels_max_upload_size',
            ]);
        });
    }
};
