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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('senders')->onDelete('cascade');
            $table->string('tracking_number')->unique();

            // Pickup Information
            $table->foreignId('pickup_address_id')->nullable()->constrained('sender_addresses')->onDelete('set null');
            $table->text('pickup_full_address');
            $table->string('pickup_country')->default('Lebanon');
            $table->string('pickup_city');
            $table->string('pickup_area')->nullable();
            $table->text('pickup_landmark')->nullable();
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            $table->dateTime('pickup_date');
            $table->time('pickup_time');

            // Delivery Information
            $table->text('delivery_full_address');
            $table->string('delivery_country')->default('Lebanon');
            $table->string('delivery_city');
            $table->string('delivery_area')->nullable();
            $table->text('delivery_landmark')->nullable();
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->dateTime('delivery_date');
            $table->time('delivery_time');

            // Receiver Information
            $table->string('receiver_name');
            $table->string('receiver_mobile');
            $table->text('receiver_notes')->nullable();

            // Package Information
            $table->foreignId('package_type_id')->constrained('package_types')->onDelete('restrict');
            $table->text('description');
            $table->decimal('weight', 8, 2); // in kg
            $table->decimal('length', 8, 2)->nullable(); // in cm
            $table->decimal('width', 8, 2)->nullable(); // in cm
            $table->decimal('height', 8, 2)->nullable(); // in cm
            $table->text('special_instructions')->nullable();
            $table->string('image')->nullable(); // package photo

            // Status and Tracking
            $table->enum('status', ['pending_review', 'approved', 'rejected', 'paid', 'in_transit', 'delivered', 'cancelled'])->default('pending_review');
            $table->boolean('compliance_confirmed')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['sender_id', 'status']);
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
