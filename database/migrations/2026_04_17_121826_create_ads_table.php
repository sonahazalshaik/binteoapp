<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('media_path'); // Path to ad video/image
            $table->string('click_url');
            $table->integer('duration')->nullable(); // In seconds
            $table->integer('skip_after')->default(5); // Seconds until skip
            $table->decimal('cpc', 8, 4)->default(0); // Cost per click
            $table->decimal('cpm', 8, 4)->default(0); // Cost per 1000 views
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
