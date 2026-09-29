<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            
            // Personal Information
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            
            // Government ID
            $table->string('id_type'); // passport, aadhaar, driving_license, voter_id, pan_card
            $table->string('id_number');
            $table->string('id_document_front')->nullable(); // R2 URL
            $table->string('id_document_back')->nullable();  // R2 URL
            
            // Selfie Verification
            $table->string('selfie_image')->nullable(); // R2 URL (live capture)
            
            // Bank Details
            $table->string('bank_name');
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('branch_name')->nullable();
            
            // Status: 0=pending, 1=approved, 2=rejected
            $table->tinyInteger('status')->default(0);
            $table->text('admin_feedback')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_submissions');
    }
};
