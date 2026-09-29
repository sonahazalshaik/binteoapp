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
            $table->string('music_source')->nullable()->after('music_id');
            $table->decimal('music_start_time', 8, 2)->default(0)->after('music_source');
            $table->string('global_music_url')->nullable()->after('music_start_time');
            $table->string('global_music_title')->nullable()->after('global_music_url');
            $table->string('global_music_artist')->nullable()->after('global_music_title');
            $table->string('global_music_thumbnail')->nullable()->after('global_music_artist');
            $table->unsignedBigInteger('original_reel_id')->nullable()->after('global_music_thumbnail');

            $table->foreign('original_reel_id')->references('id')->on('reels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reels', function (Blueprint $table) {
            $table->dropForeign(['original_reel_id']);
            $table->dropColumn([
                'music_source', 'music_start_time', 'global_music_url',
                'global_music_title', 'global_music_artist', 'global_music_thumbnail',
                'original_reel_id'
            ]);
        });
    }
};
