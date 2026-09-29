<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id')->default(0);
            $table->unsignedBigInteger('purchaser_id')->default(0);
            $table->unsignedBigInteger('creator_id')->default(0);
            $table->decimal('total_amount', 28, 8)->default(0);
            $table->decimal('admin_commission', 28, 8)->default(0);
            $table->decimal('creator_commission', 28, 8)->default(0);
            $table->string('trx')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_commissions');
    }
};
