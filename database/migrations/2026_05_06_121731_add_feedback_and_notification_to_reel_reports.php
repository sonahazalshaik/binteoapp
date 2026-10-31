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
        Schema::table('reel_reports', function (Blueprint $table) {
            $table->text('admin_feedback')->nullable()->after('status');
            $table->boolean('is_notified')->default(false)->after('admin_feedback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reel_reports', function (Blueprint $table) {
            $table->dropColumn(['admin_feedback', 'is_notified']);
        });
    }
};
