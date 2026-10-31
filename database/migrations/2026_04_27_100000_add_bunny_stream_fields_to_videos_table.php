<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add Bunny Stream fields to the videos table.
     *
     * bunny_id:     The GUID returned by Bunny when a video slot is created.
     * bunny_status: Tracks the encoding lifecycle (uploading → processing → ready → error).
     */
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('bunny_id')->nullable()->after('slug')->index();
            $table->string('bunny_status')->nullable()->default(null)->after('bunny_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropIndex(['bunny_id']);
            $table->dropColumn(['bunny_id', 'bunny_status']);
        });
    }
};
