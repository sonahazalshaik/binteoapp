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
        Schema::table('market_places', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name')->unique();
        });

        // Seed existing data safely in chunks for the live database
        \App\Models\MarketPlace::chunk(100, function ($marketplaces) {
            foreach ($marketplaces as $item) {
                // Unset slug to let the new MarketPlace boot logic handle the unique slug generation accurately
                $item->slug = null; 
                $item->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('market_places', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
