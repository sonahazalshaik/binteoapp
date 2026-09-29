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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('following_id')->after('channel_id')->nullable();
        });

        // Sync existing data
        \DB::statement("UPDATE subscriptions s JOIN channels c ON s.channel_id = c.id SET s.following_id = c.user_id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('following_id');
        });
    }
};
