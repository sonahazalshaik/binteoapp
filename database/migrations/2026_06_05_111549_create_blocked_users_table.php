<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("blocked_users", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger("blocked_user_id");
            $table->foreign("blocked_user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->timestamps();
            $table->unique(["user_id", "blocked_user_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("blocked_users");
    }
};
