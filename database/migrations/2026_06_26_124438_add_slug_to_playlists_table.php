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
        Schema::table('playlists', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name')->unique();
        });

        // Seed existing data
        \App\Models\Playlist::chunk(100, function ($playlists) {
            foreach ($playlists as $playlist) {
                // Generate a unique slug based on the playlist name
                $base = \Illuminate\Support\Str::slug($playlist->name ?: 'playlist');
                if (empty($base)) $base = 'playlist';
                
                $slug = $base;
                $counter = 1;
                while (\App\Models\Playlist::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $counter;
                    $counter++;
                }
                
                $playlist->slug = $slug;
                $playlist->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
