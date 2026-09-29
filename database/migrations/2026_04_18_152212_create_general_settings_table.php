<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable();
            $table->string('cur_sym')->default('$');
            $table->string('cur_text')->default('USD');
            $table->string('base_color')->default('EA001F');
            $table->string('secondary_color')->default('000000');
            $table->string('currency_format')->default('1'); 
            $table->integer('paginate_number')->default(20);
            $table->text('mail_config')->nullable();
            $table->text('sms_config')->nullable();
            $table->text('global_shortcodes')->nullable();
            $table->text('socialite_credentials')->nullable();
            $table->text('firebase_config')->nullable();
            $table->text('config_progress')->nullable();
            $table->text('vc_warning')->nullable();
            $table->text('ad_config')->nullable();
            $table->text('off_days')->nullable();
            $table->text('ftp')->nullable();
            $table->text('wasabi')->nullable();
            $table->text('digital_ocean')->nullable();
            $table->tinyInteger('ads_module')->default(1);
            $table->timestamps();
        });

        // Seed initial row
        DB::table('general_settings')->insert([
            'site_name' => 'of2on tube',
            'cur_sym' => '$',
            'cur_text' => 'USD',
            'paginate_number' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
