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
        Schema::table('market_messages', function (Blueprint $table) {
            $table->foreignId('sender_marketplace_id')->nullable()->constrained('market_places')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('market_messages', function (Blueprint $table) {
            $table->dropForeign(['sender_marketplace_id']);
            $table->dropColumn('sender_marketplace_id');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
