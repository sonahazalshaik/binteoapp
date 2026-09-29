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
        Schema::create('cron_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('interval')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->string('url')->nullable();
            $table->timestamp('next_run')->nullable();
            $table->timestamp('last_run')->nullable();
            $table->foreignId('cron_schedule_id')->nullable()->constrained('cron_schedules')->onDelete('set null');
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('is_running')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('cron_job_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cron_job_id')->constrained('cron_jobs')->onDelete('cascade');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('duration')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });

        // Seed some default schedules
        \DB::table('cron_schedules')->insert([
            ['name' => '5 Minutes', 'interval' => 300, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '10 Minutes', 'interval' => 600, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '1 Hour', 'interval' => 3600, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Daily', 'interval' => 86400, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_job_logs');
        Schema::dropIfExists('cron_jobs');
        Schema::dropIfExists('cron_schedules');
    }
};
