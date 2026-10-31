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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'country_code')) $table->string('country_code')->nullable();
            if (!Schema::hasColumn('users', 'username')) $table->string('username')->nullable()->unique();
            if (!Schema::hasColumn('users', 'address')) $table->string('address')->nullable();
            if (!Schema::hasColumn('users', 'city')) $table->string('city')->nullable();
            if (!Schema::hasColumn('users', 'state')) $table->string('state')->nullable();
            if (!Schema::hasColumn('users', 'zip')) $table->string('zip')->nullable();
            if (!Schema::hasColumn('users', 'country_name')) $table->string('country_name')->nullable();
            if (!Schema::hasColumn('users', 'slug')) $table->string('slug')->nullable()->unique();
            if (!Schema::hasColumn('users', 'profile_complete')) $table->tinyInteger('profile_complete')->default(0);
            if (!Schema::hasColumn('users', 'cover_image')) $table->string('cover_image')->nullable();
            if (!Schema::hasColumn('users', 'image')) $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'country_code', 'username', 'address', 'city', 'state', 'zip', 
                'country_name', 'slug', 'profile_complete', 'cover_image', 'image'
            ]);
        });
    }
};
