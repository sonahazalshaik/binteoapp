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
        Schema::table('videos', function (Blueprint $table) {
            // Change status to string to handle both integer constants and legacy strings
            $table->string('status', 40)->default('processing')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
             $table->enum('status', ['processing', 'ready', 'draft', 'published', 'rejected'])->default('draft')->change();
        });
    }
};
