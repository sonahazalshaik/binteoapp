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
        Schema::table('reel_music', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Seed existing data
        \App\Models\ReelMusic::chunk(100, function ($musics) {
            foreach ($musics as $music) {
                $baseSlug = \Illuminate\Support\Str::slug($music->title ?: 'music');
                $slug = $baseSlug;
                $counter = 1;
                while (\App\Models\ReelMusic::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . \Illuminate\Support\Str::random(4);
                    $counter++;
                    if ($counter > 10) {
                        $slug = $baseSlug . '-' . uniqid();
                    }
                }
                $music->slug = $slug;
                $music->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reel_music', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
