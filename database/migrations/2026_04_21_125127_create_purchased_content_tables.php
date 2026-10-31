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
        if (!Schema::hasTable('purchased_playlists')) {
            Schema::create('purchased_playlists', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('playlist_id')->constrained('playlists')->onDelete('cascade');
                $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->decimal('price', 28, 8)->default(0);
                $table->string('trx')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('purchased_plans')) {
            Schema::create('purchased_plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade'); // Assuming 'plans' table exists (it does from previous check)
                $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->decimal('price', 28, 8)->default(0);
                $table->string('trx')->nullable();
                $table->timestamp('expired_date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchased_plans');
        Schema::dropIfExists('purchased_playlists');
    }
};
