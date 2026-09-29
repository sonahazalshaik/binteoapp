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
        Schema::create('ott_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 18, 8)->default(0);
            $table->text('description')->nullable();
            $table->integer('duration')->default(30); // in days
            $table->boolean('ott_access')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ott_plans');
    }
};
