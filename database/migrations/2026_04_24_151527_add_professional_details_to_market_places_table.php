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
            $table->string('years_of_experience')->nullable();
            $table->text('skills')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('market_places', function (Blueprint $table) {
            $table->dropColumn(['years_of_experience', 'skills', 'portfolio_url', 'website_url', 'business_name', 'business_type']);
        });
    }
};
