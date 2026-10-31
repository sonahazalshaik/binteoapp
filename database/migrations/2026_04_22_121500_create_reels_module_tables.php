<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // ─── Reusable Music / Audio Tracks ───
        Schema::create('reel_music', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // null = platform-uploaded
            $table->string('title');
            $table->string('artist')->nullable();
            $table->string('file_path');               // stored audio file
            $table->integer('duration')->default(0);    // seconds
            $table->string('cover_image')->nullable();  // album art
            $table->string('genre')->nullable();
            $table->bigInteger('usage_count')->default(0); // how many reels use this
            $table->boolean('status')->default(true);   // active / disabled
            $table->timestamps();
        });

        // ─── Primary Reels Table ───
        Schema::create('reels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('music_id')->nullable()->constrained('reel_music')->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();    // supports #hashtags and @mentions

            // Media
            $table->string('video_path');
            $table->string('compressed_video_path')->nullable(); // post-compression
            $table->string('thumbnail_path')->nullable();
            $table->integer('duration')->default(0);    // seconds (max 90)

            // Counters (cached)
            $table->bigInteger('views_count')->default(0);
            $table->bigInteger('likes_count')->default(0);
            $table->bigInteger('comments_count')->default(0);
            $table->bigInteger('shares_count')->default(0);

            // Visibility & Status
            $table->tinyInteger('visibility')->default(0);  // 0=public, 1=private
            $table->tinyInteger('status')->default(0);      // 0=draft, 1=published, 2=rejected
            $table->string('moderation_status')->nullable();
            $table->boolean('is_age_restricted')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->string('location')->nullable();

            // Audio / Music
            $table->string('audio_name')->nullable();    // custom audio label
            $table->string('audio_path')->nullable();    // uploaded audio with the reel

            // Interaction Toggles
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_duet')->default(true);
            $table->boolean('allow_stitch')->default(true);

            // Compression tracking
            $table->boolean('is_compressed')->default(false);
            $table->tinyInteger('compression_status')->default(0); // 0=pending, 1=processing, 2=done, 3=failed

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'visibility']);
            $table->index('is_trending');
        });

        // ─── Reel Likes (Heart) ───
        Schema::create('reel_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('is_like')->default(1); // 1=like (heart), 0=dislike
            $table->timestamps();

            $table->unique(['reel_id', 'user_id']);
        });

        // ─── Reel Comments ───
        Schema::create('reel_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('reel_comments')->cascadeOnDelete();
            $table->text('content');    // supports @mentions inline
            $table->timestamps();

            $table->index(['reel_id', 'created_at']);
        });

        // ─── Reel Hashtags ───
        Schema::create('reel_hashtags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->string('hashtag', 100); // stored without # prefix
            $table->timestamps();

            $table->index('hashtag');
        });

        // ─── Reel @Mentions ───
        Schema::create('reel_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mentioned_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('source', ['description', 'comment'])->default('description');
            $table->foreignId('comment_id')->nullable()->constrained('reel_comments')->cascadeOnDelete();
            $table->timestamps();

            $table->index('mentioned_user_id');
        });

        // ─── Reel Tags (general tags, separate from hashtags) ───
        Schema::create('reel_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->string('tag', 100);
            $table->timestamps();
        });

        // ─── Reel Reports ───
        Schema::create('reel_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reason');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(0); // 0=pending, 1=resolved
            $table->timestamps();
        });

        // ─── Reel View Logs (analytics) ───
        Schema::create('reel_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip', 45)->nullable();
            $table->timestamps();

            $table->index(['reel_id', 'created_at']);
        });

        // ─── Reel Shares ───
        Schema::create('reel_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('platform')->nullable(); // whatsapp, copy_link, twitter, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reel_shares');
        Schema::dropIfExists('reel_views');
        Schema::dropIfExists('reel_reports');
        Schema::dropIfExists('reel_tags');
        Schema::dropIfExists('reel_mentions');
        Schema::dropIfExists('reel_hashtags');
        Schema::dropIfExists('reel_comments');
        Schema::dropIfExists('reel_likes');
        Schema::dropIfExists('reels');
        Schema::dropIfExists('reel_music');
    }
};
