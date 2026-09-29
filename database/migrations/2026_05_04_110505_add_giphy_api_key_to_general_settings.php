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
            $table->string('giphy_api_key')->nullable();
        });

        // Seed the value immediately
        \DB::table('general_settings')->update(['giphy_api_key' => 'Glg3zNDKNzJpcaTkmIfuLVnXzBqZebIF']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('giphy_api_key');
        });
    }
};
