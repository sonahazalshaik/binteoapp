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
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('retention_25')->default(0)->after('total_watch_time');
            $table->integer('retention_50')->default(0)->after('retention_25');
            $table->integer('retention_75')->default(0)->after('retention_50');
            $table->integer('retention_100')->default(0)->after('retention_75');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['retention_25', 'retention_50', 'retention_75', 'retention_100']);
        });
    }
};
