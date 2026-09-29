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
        Schema::table('advertisements', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisements', 'ad_module')) $table->tinyInteger('ad_module')->default(0);
            if (!Schema::hasColumn('advertisements', 'total_amount')) $table->decimal('total_amount', 28, 8)->default(0);
            if (!Schema::hasColumn('advertisements', 'available_impression')) $table->integer('available_impression')->default(0);
            if (!Schema::hasColumn('advertisements', 'step')) $table->tinyInteger('step')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['ad_module', 'total_amount', 'available_impression', 'step']);
        });
    }
};
