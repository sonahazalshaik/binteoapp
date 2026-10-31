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
        Schema::create('admin_channel_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('duration_type', 50)->unique();
            $table->unsignedInteger('duration_days')->default(30);
            $table->unsignedInteger('duration_months')->default(1);
            $table->decimal('default_price', 18, 8)->default(0);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_channel_plans');
    }
};
