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
        Schema::create('extensions', function (Blueprint $table) {
            $table->id();
            $table->string('act')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->text('script')->nullable();
            $table->text('shortcode')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        // Seed basic extensions
        DB::table('extensions')->insert([
            [
                'act' => 'google-recaptcha2',
                'name' => 'Google Recaptcha 2',
                'description' => 'Google reCAPTCHA is a free service that protects your website from spam and abuse.',
                'image' => 'recaptcha.png',
                'script' => '<script src="https://www.google.com/recaptcha/api.js" async defer></script><div class="g-recaptcha" data-sitekey="{{site_key}}"></div>',
                'shortcode' => json_encode([
                    'site_key' => ['title' => 'Site Key', 'value' => ''],
                    'secret_key' => ['title' => 'Secret Key', 'value' => '']
                ]),
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'act' => 'custom-captcha',
                'name' => 'Custom Captcha',
                'description' => 'Custom captcha for your website.',
                'image' => 'custom_captcha.png',
                'script' => '<div class="form-group"><input type="text" name="captcha" placeholder="Enter Code" class="form-control" required></div>',
                'shortcode' => json_encode([
                    'width' => ['title' => 'Width', 'value' => '100%'],
                    'height' => ['title' => 'Height', 'value' => '46'],
                    'random_key' => ['title' => 'Random Key', 'value' => Str::random(16)]
                ]),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extensions');
    }
};
