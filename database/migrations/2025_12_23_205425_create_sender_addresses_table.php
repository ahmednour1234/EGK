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
        Schema::create('sender_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('senders')->onDelete('cascade');
            $table->string('title'); // e.g., Home, Office, Warehouse
            $table->enum('type', ['home', 'office', 'warehouse', 'other'])->default('home');
            $table->boolean('is_default')->default(false);
            $table->text('full_address'); // Street, building, floor
            $table->string('mobile_number')->nullable();
            $table->string('country')->default('Lebanon');
            $table->string('city');
            $table->string('area')->nullable(); // Area/District
            $table->text('landmark')->nullable(); // Optional landmark
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['sender_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sender_addresses');
    }
};
