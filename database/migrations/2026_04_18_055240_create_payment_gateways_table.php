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
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('alias')->unique();
            $table->string('name');
            $table->tinyInteger('status')->default(1);
            $table->text('gateway_parameter')->nullable();
            $table->timestamps();
        });

        Schema::create('gateway_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('currency');
            $table->string('symbol');
            $table->integer('method_code');
            $table->foreignId('gateway_id')->constrained('gateways')->onDelete('cascade');
            $table->decimal('min_amount', 12, 2)->default(0);
            $table->decimal('max_amount', 12, 2)->default(0);
            $table->decimal('fixed_charge', 12, 2)->default(0);
            $table->decimal('percent_charge', 5, 2)->default(0);
            $table->decimal('rate', 12, 8)->default(1);
            $table->text('gateway_parameter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_currencies');
        Schema::dropIfExists('gateways');
    }
};
