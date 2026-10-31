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
        Schema::create('channel_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('channel_id')->constrained('channels')->onDelete('cascade');
            $table->foreignId('channel_plan_id')->constrained('channel_plans')->onDelete('cascade');
            $table->string('plan_name')->nullable();
            $table->decimal('amount', 18, 8)->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->string('trx', 100)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'channel_id', 'status', 'expires_at'], 'user_channel_sub_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_subscriptions');
    }
};
