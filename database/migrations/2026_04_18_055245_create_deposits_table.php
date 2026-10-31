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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('marketplace_id')->nullable()->constrained('market_places')->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('user_plans')->onDelete('cascade');
            $table->integer('method_code');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('method_currency');
            $table->decimal('charge', 12, 2)->default(0);
            $table->decimal('rate', 12, 8)->default(1);
            $table->decimal('final_amount', 12, 2)->default(0);
            $table->string('trx')->unique();
            $table->string('btc_wallet')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1=>success, 2=>pending, 3=>cancel');
            $table->text('detail')->nullable();
            $table->string('success_url')->nullable();
            $table->string('failed_url')->nullable();
            $table->timestamps();
            
            // Add custom plan_id relation if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
