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
        Schema::table('playlists', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->tinyInteger('visibility')->default(0)->comment('0: Public, 1: Private');
            $table->decimal('price', 18, 8)->default(0);
            $table->tinyInteger('playlist_subscription')->default(0)->comment('0: Disable, 1: Enable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropColumn(['description', 'visibility', 'price', 'playlist_subscription']);
        });
    }
};
