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
        Schema::create('saved_audios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reel_music_id')->constrained('reel_music')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'reel_music_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_audios');
    }
};
