<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'mini_ott_status')) {
                $table->tinyInteger('mini_ott_status')->default(1)->after('ads_module')->comment('1=active,0=coming_soon');
            }
        });
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (Schema::hasColumn('general_settings', 'mini_ott_status')) {
                $table->dropColumn('mini_ott_status');
            }
        });
    }
};
