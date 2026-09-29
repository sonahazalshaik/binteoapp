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
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('total_watch_time')->default(0); // in seconds
        });

        Schema::table('view_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('watch_time')->default(0); // in seconds
            $table->boolean('is_completed')->default(false);
            $table->string('device')->nullable();
            $table->string('country')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('strike_count')->default(0);
            $table->timestamp('restriction_ends_at')->nullable();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('reported_user_id')->nullable();
            $table->text('admin_feedback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['impressions', 'total_watch_time']);
        });

        Schema::table('view_logs', function (Blueprint $table) {
            $table->dropColumn(['watch_time', 'is_completed', 'device', 'country']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['strike_count', 'restriction_ends_at']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['reported_user_id', 'admin_feedback']);
        });
    }
};
