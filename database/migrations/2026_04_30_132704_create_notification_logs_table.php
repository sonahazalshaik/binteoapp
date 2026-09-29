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
        Schema::create('notification_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->unsignedBigInteger('user_id')->default(0);
            $blueprint->string('notification_type', 40)->nullable();
            $blueprint->string('sender', 40)->nullable();
            $blueprint->string('sent_from', 40)->nullable();
            $blueprint->string('sent_to', 40)->nullable();
            $blueprint->string('subject')->nullable();
            $blueprint->text('message')->nullable();
            $blueprint->string('image')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
