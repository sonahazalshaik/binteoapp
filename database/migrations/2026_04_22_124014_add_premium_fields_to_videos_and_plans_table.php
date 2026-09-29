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
        Schema::table('videos', function (Blueprint $table) {
            $table->boolean('is_premium')->default(0)->after('duration');
            $table->decimal('price', 28, 8)->default(0)->after('is_premium');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('video_access')->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['is_premium', 'price']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('video_access');
        });
    }
};
