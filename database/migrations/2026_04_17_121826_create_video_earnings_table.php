<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('ad_impressions')->default(0);
            $table->integer('ad_clicks')->default(0);
            $table->decimal('estimated_revenue', 10, 4)->default(0); // Daily revenue
            $table->timestamps();

            $table->unique(['video_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_earnings');
    }
};
