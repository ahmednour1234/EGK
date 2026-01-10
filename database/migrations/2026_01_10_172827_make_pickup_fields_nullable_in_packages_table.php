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
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE packages MODIFY pickup_full_address TEXT NULL');
            DB::statement('ALTER TABLE packages MODIFY pickup_country VARCHAR(255) NULL DEFAULT NULL');
            DB::statement('ALTER TABLE packages MODIFY pickup_city VARCHAR(255) NULL');
            DB::statement('ALTER TABLE packages MODIFY pickup_date DATETIME NULL');
            DB::statement('ALTER TABLE packages MODIFY pickup_time TIME NULL');
            DB::statement('ALTER TABLE packages MODIFY delivery_country VARCHAR(255) NULL DEFAULT NULL');
        } else {
            Schema::table('packages', function (Blueprint $table) {
                $table->text('pickup_full_address')->nullable()->change();
                $table->string('pickup_country')->nullable()->change();
                $table->string('pickup_city')->nullable()->change();
                $table->dateTime('pickup_date')->nullable()->change();
                $table->time('pickup_time')->nullable()->change();
                $table->string('delivery_country')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE packages MODIFY pickup_full_address TEXT NOT NULL');
            DB::statement('ALTER TABLE packages MODIFY pickup_country VARCHAR(255) NOT NULL DEFAULT "Lebanon"');
            DB::statement('ALTER TABLE packages MODIFY pickup_city VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE packages MODIFY pickup_date DATETIME NOT NULL');
            DB::statement('ALTER TABLE packages MODIFY pickup_time TIME NOT NULL');
            DB::statement('ALTER TABLE packages MODIFY delivery_country VARCHAR(255) NOT NULL DEFAULT "Lebanon"');
        } else {
            Schema::table('packages', function (Blueprint $table) {
                $table->text('pickup_full_address')->nullable(false)->change();
                $table->string('pickup_country')->nullable(false)->default('Lebanon')->change();
                $table->string('pickup_city')->nullable(false)->change();
                $table->dateTime('pickup_date')->nullable(false)->change();
                $table->time('pickup_time')->nullable(false)->change();
                $table->string('delivery_country')->nullable(false)->default('Lebanon')->change();
            });
        }
    }
};
