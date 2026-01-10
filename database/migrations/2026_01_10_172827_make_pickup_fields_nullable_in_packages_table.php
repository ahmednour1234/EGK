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
        Schema::table('packages', function (Blueprint $table) {
            $table->text('pickup_full_address')->nullable()->change();
            $table->string('pickup_city')->nullable()->change();
            $table->dateTime('pickup_date')->nullable()->change();
            $table->time('pickup_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->text('pickup_full_address')->nullable(false)->change();
            $table->string('pickup_city')->nullable(false)->change();
            $table->dateTime('pickup_date')->nullable(false)->change();
            $table->time('pickup_time')->nullable(false)->change();
        });
    }
};
