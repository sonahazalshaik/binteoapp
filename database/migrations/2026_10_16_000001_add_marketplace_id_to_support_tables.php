<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('support_tickets', 'marketplace_id')) {
                $table->foreignId('marketplace_id')->nullable()->after('user_id')->constrained('market_places')->onDelete('cascade');
            }
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_notifications', 'marketplace_id')) {
                $table->foreignId('marketplace_id')->nullable()->after('user_id')->constrained('market_places')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admin_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('admin_notifications', 'marketplace_id')) {
                $table->dropConstrainedForeignId('marketplace_id');
            }
        });

        Schema::table('support_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('support_tickets', 'marketplace_id')) {
                $table->dropConstrainedForeignId('marketplace_id');
            }
        });
    }
};
