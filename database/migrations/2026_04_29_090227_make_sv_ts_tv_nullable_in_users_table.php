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
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY sv TINYINT NULL, MODIFY ts TINYINT NULL, MODIFY tv TINYINT NULL;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY sv TINYINT NOT NULL DEFAULT 1, MODIFY ts TINYINT NOT NULL DEFAULT 0, MODIFY tv TINYINT NOT NULL DEFAULT 1;");
    }
};
