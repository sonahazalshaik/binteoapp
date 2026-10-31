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
        Schema::table('view_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('video_id')->nullable()->change();
            $table->foreignId('reel_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('view_logs', function (Blueprint $table) {
            $table->dropForeign(['reel_id']);
            $table->dropColumn('reel_id');
            $table->unsignedBigInteger('video_id')->nullable(false)->change();
        });
    }
};
