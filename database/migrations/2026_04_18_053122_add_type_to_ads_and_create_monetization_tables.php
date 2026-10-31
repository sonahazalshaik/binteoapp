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
        Schema::table('ads', function (Blueprint $table) {
            $table->enum('type', ['pre-roll', 'mid-roll', 'banner'])->default('pre-roll')->after('title');
        });

        Schema::create('ad_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('monetization_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_monetization_enabled')->default(true);
            $table->decimal('platform_commission', 5, 2)->default(30.00); // 30% commission
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monetization_settings');
        Schema::dropIfExists('ad_video');
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
