<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\BannerAd;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banner_ads', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
        });

        // Seed existing banners with slugs
        $banners = BannerAd::all();
        foreach ($banners as $banner) {
            $banner->slug = 'ad-' . uniqid() . '-' . Str::random(5);
            $banner->save();
        }

        // Now make it unique
        Schema::table('banner_ads', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_ads', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
