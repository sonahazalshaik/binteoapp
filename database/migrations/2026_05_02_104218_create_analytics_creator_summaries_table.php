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
        Schema::create('analytics_creator_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('recorded_at');
            $table->integer('views')->default(0);
            $table->float('watch_time_minutes')->default(0);
            $table->integer('subscribers_gained')->default(0);
            $table->decimal('earnings', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'recorded_at']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_creator_summaries');
    }
};
