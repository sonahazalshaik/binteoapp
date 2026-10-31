<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reels', function (Blueprint $table) {
            $table->string('bunny_id')->nullable()->after('slug')->index();
            $table->string('bunny_status')->nullable()->default(null)->after('bunny_id');
        });
    }

    public function down(): void
    {
        Schema::table('reels', function (Blueprint $table) {
            $table->dropIndex(['bunny_id']);
            $table->dropColumn(['bunny_id', 'bunny_status']);
        });
    }
};
