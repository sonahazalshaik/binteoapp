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
        Schema::create('market_places', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('number')->nullable();
            $table->string('email')->unique();
            $table->text('more_info')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('password');
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->timestamps();
        });

        Schema::create('market_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_id')->constrained('market_places')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('market_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_id')->constrained('market_places')->onDelete('cascade');
            $table->string('market_gallery');
            $table->timestamps();
        });

        Schema::create('market_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_id')->constrained('market_places')->onDelete('cascade');
            $table->string('service_img')->nullable();
            $table->string('service_name');
            $table->text('service_brief')->nullable();
            $table->timestamps();
        });

        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->decimal('plan_price', 12, 2)->default(0);
            $table->integer('plan_duration')->comment('in days');
            $table->text('plan_content')->nullable();
            $table->timestamps();
        });

        Schema::create('market_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_id')->constrained('market_places')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('user_plans')->onDelete('cascade');
            $table->string('plan_name')->nullable();
            $table->decimal('plan_price', 12, 2)->default(0);
            $table->integer('plan_duration')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('market_subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketplace_id')->constrained('market_places')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('user_plans')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('gateway_code')->nullable();
            $table->string('trx')->unique();
            $table->tinyInteger('status')->default(0);
            $table->text('detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_subscription_payments');
        Schema::dropIfExists('market_subscriptions');
        Schema::dropIfExists('user_plans');
        Schema::dropIfExists('market_services');
        Schema::dropIfExists('market_galleries');
        Schema::dropIfExists('market_contacts');
        Schema::dropIfExists('market_places');
    }
};
