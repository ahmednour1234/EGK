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
        Schema::create('traveler_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traveler_id')->constrained('senders')->onDelete('cascade');
            
            // Trip Information
            $table->string('from_city');
            $table->string('to_city');
            $table->string('full_address');
            $table->string('landmark')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('trip_type', ['one-way', 'round-trip'])->default('one-way');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->date('return_date')->nullable(); // For round trips
            $table->time('return_time')->nullable(); // For round trips
            $table->string('transport_type'); // e.g., Car, Truck, Motorcycle
            
            // Travel Capacity
            $table->decimal('total_weight_limit', 8, 2)->nullable(); // in kg
            $table->integer('max_package_count')->nullable();
            $table->json('acceptable_package_types')->nullable(); // Array of package type IDs
            $table->string('preferred_pickup_area')->nullable();
            $table->string('preferred_delivery_area')->nullable();
            
            // Notes & Special Conditions
            $table->text('notes_for_senders')->nullable();
            $table->boolean('allow_urgent_packages')->default(false);
            $table->boolean('accept_only_verified_senders')->default(false);
            
            // Status
            $table->enum('status', ['draft', 'active', 'matched', 'completed', 'cancelled'])->default('draft');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['traveler_id', 'status']);
            $table->index(['from_city', 'to_city']);
            $table->index('departure_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traveler_tickets');
    }
};
