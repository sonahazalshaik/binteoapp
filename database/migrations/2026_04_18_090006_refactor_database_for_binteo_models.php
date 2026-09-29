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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'firstname')) $table->string('firstname')->nullable();
            if (!Schema::hasColumn('users', 'lastname')) $table->string('lastname')->nullable();
            if (!Schema::hasColumn('users', 'dial_code')) $table->string('dial_code')->nullable();
            if (!Schema::hasColumn('users', 'mobile')) $table->string('mobile')->nullable();
            if (!Schema::hasColumn('users', 'channel_name')) $table->string('channel_name')->nullable();
            if (!Schema::hasColumn('users', 'ev')) $table->tinyInteger('ev')->default(1);
            if (!Schema::hasColumn('users', 'sv')) $table->tinyInteger('sv')->default(1);
            if (!Schema::hasColumn('users', 'kv')) $table->tinyInteger('kv')->default(0);
            if (!Schema::hasColumn('users', 'balance')) $table->decimal('balance', 28, 8)->default(0);
            if (!Schema::hasColumn('users', 'monetization_status')) $table->tinyInteger('monetization_status')->default(0);
            if (!Schema::hasColumn('users', 'advertiser_status')) $table->tinyInteger('advertiser_status')->default(0);
            if (!Schema::hasColumn('users', 'kyc_data')) $table->text('kyc_data')->nullable();
            if (!Schema::hasColumn('users', 'advertiser_data')) $table->text('advertiser_data')->nullable();
            if (!Schema::hasColumn('users', 'social_links')) $table->text('social_links')->nullable();
            if (!Schema::hasColumn('users', 'ver_code')) $table->string('ver_code')->nullable();
            if (!Schema::hasColumn('users', 'ver_code_send_at')) $table->timestamp('ver_code_send_at')->nullable();
        });

        if (!Schema::hasTable('plans')) {
            Schema::create('plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('name');
                $table->decimal('price', 28, 8)->default(0);
                $table->integer('duration')->default(30);
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_plans')) {
            Schema::create('user_plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('plan_name')->nullable();
                $table->decimal('plan_price', 28, 8)->default(0);
                $table->integer('plan_duration')->default(30);
                $table->text('plan_content')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_plans');
        Schema::dropIfExists('plans');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'firstname', 'lastname', 'dial_code', 'mobile', 'channel_name',
                'status', 'ev', 'sv', 'kv', 'balance', 'monetization_status', 
                'advertiser_status', 'kyc_data', 'advertiser_data', 'social_links', 
                'ver_code', 'ver_code_send_at'
            ]);
        });
    }
};
