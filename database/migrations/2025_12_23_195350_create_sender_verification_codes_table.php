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
        Schema::create('sender_verification_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('senders')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('code', 6);
            $table->enum('type', ['email_verification', 'password_reset', 'phone_verification'])->default('email_verification');
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['email', 'code', 'is_used']);
            $table->index(['phone', 'code', 'is_used']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sender_verification_codes');
    }
};
