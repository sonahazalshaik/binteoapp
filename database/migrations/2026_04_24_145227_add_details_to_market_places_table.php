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
        Schema::table('market_places', function (Blueprint $table) {
            $table->string('location')->nullable()->after('email');
            $table->float('rating')->default(5.0)->after('location');
            $table->integer('projects_count')->default(0)->after('rating');
            $table->integer('satisfaction_rate')->default(100)->after('projects_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('market_places', function (Blueprint $table) {
            $table->dropColumn(['location', 'rating', 'projects_count', 'satisfaction_rate']);
        });
    }
};
