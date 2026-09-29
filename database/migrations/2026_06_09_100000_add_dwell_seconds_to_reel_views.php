<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('reel_views') && ! Schema::hasColumn('reel_views', 'dwell_seconds')) {
            Schema::table('reel_views', function (Blueprint $table) {
                $table->unsignedInteger('dwell_seconds')->default(0)->after('ip');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('reel_views') && Schema::hasColumn('reel_views', 'dwell_seconds')) {
            Schema::table('reel_views', function (Blueprint $table) {
                $table->dropColumn('dwell_seconds');
            });
        }
    }
};
