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
        Schema::dropIfExists('packages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate packages table if needed (for rollback)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('package_type_id')->nullable()->constrained('package_types')->onDelete('set null');
            $table->foreignId('pickup_city_id')->constrained('cities')->onDelete('restrict');
            $table->foreignId('delivery_city_id')->constrained('cities')->onDelete('restrict');
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_email')->nullable();
            $table->text('sender_address');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();
            $table->text('receiver_address');
            $table->text('description')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('pickup_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
