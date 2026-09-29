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
            $table->string('google_api_key')->nullable();
            $table->integer('minimum_subscribe')->default(0);
            $table->integer('minimum_views')->default(0);
            $table->integer('watch_hours')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['google_api_key', 'minimum_subscribe', 'minimum_views', 'watch_hours']);
        });
    }
};
