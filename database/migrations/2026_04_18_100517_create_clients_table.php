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
        Schema::create('clients', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('name')->nullable();
            $blueprint->string('firstname')->nullable();
            $blueprint->string('lastname')->nullable();
            $blueprint->string('username')->unique()->nullable();
            $blueprint->string('email')->unique();
            $blueprint->timestamp('email_verified_at')->nullable();
            $blueprint->string('password');
            $blueprint->string('role')->default('user'); // For internal consistency if needed
            $blueprint->string('status')->default('active');
            $blueprint->string('dial_code')->nullable();
            $blueprint->string('mobile')->nullable();
            $blueprint->string('channel_name')->nullable();
            $blueprint->integer('ev')->default(0);
            $blueprint->integer('sv')->default(0);
            $blueprint->integer('kv')->default(0);
            $blueprint->decimal('balance', 28, 8)->default(0);
            $blueprint->integer('monetization_status')->default(0);
            $blueprint->integer('advertiser_status')->default(0);
            $blueprint->text('kyc_data')->nullable();
            $blueprint->text('advertiser_data')->nullable();
            $blueprint->text('social_links')->nullable();
            $blueprint->string('ver_code', 40)->nullable();
            $blueprint->timestamp('ver_code_send_at')->nullable();
            $blueprint->string('country_code', 40)->nullable();
            $blueprint->string('address')->nullable();
            $blueprint->string('city')->nullable();
            $blueprint->string('state')->nullable();
            $blueprint->string('zip')->nullable();
            $blueprint->string('country_name')->nullable();
            $blueprint->string('slug')->unique()->nullable();
            $blueprint->integer('profile_complete')->default(0);
            $blueprint->string('cover_image')->nullable();
            $blueprint->string('image')->nullable();
            $blueprint->rememberToken();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
