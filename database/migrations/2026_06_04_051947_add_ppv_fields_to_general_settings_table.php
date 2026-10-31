<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->decimal('ppv_creator_commission_percent', 5, 2)->default(70.00);
            $table->integer('ppv_teaser_duration_seconds')->default(30);
        });
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['ppv_creator_commission_percent', 'ppv_teaser_duration_seconds']);
        });
    }
};
