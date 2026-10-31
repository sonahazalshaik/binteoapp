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
        Schema::table('channels', function (Blueprint $table) {
            if (!Schema::hasColumn('channels', 'is_featured')) {
                $table->boolean('is_featured')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('channels', 'is_trending')) {
                $table->boolean('is_trending')->default(0)->after('is_featured');
            }
        });
 
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'last_seen')) {
                $table->timestamp('last_seen')->nullable()->after('updated_at');
            }
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_trending']);
        });
 
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_seen');
        });
    }
};
