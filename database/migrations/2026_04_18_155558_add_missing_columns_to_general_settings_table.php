<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->tinyInteger('registration')->default(1);
            $table->tinyInteger('ev')->default(0)->comment('email verification');
            $table->tinyInteger('en')->default(0)->comment('email notification');
            $table->tinyInteger('sv')->default(0)->comment('sms verification');
            $table->tinyInteger('sn')->default(0)->comment('sms notification');
            $table->tinyInteger('kv')->default(0)->comment('kyc verification');
            $table->tinyInteger('force_ssl')->default(0);
            $table->tinyInteger('secure_password')->default(0);
            $table->tinyInteger('agree')->default(0);
            $table->tinyInteger('multi_language')->default(0);
            $table->tinyInteger('is_storage')->default(1);
            $table->tinyInteger('is_playlist_sell')->default(1);
            $table->tinyInteger('is_monthly_subscription')->default(1);
            $table->tinyInteger('ffmpeg_status')->default(0);
            $table->tinyInteger('ads_auto_approve')->default(0);
            $table->decimal('monetization_amount', 28, 8)->default(0);
            $table->tinyInteger('monetization_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'registration', 'ev', 'en', 'sv', 'sn', 'kv', 'force_ssl', 
                'secure_password', 'agree', 'multi_language', 'is_storage', 
                'is_playlist_sell', 'is_monthly_subscription', 'ffmpeg_status',
                'ads_auto_approve', 'monetization_amount', 'monetization_status'
            ]);
        });
    }
};
