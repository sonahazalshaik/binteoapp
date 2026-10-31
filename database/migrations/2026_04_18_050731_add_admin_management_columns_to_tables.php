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
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('active')->after('role'); // active, banned
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('banner');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string('moderation_status')->default('pending')->after('status'); // pending, approved, rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('moderation_status');
        });
    }
};
