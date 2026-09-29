<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->tinyInteger('pricing_tier')->default(0)->comment('0: Free, 1: Premium, 2: Exclusive Mini OTT');
            $table->tinyInteger('is_mini_ott')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['pricing_tier', 'is_mini_ott']);
        });
    }
};
