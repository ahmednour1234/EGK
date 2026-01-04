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
        Schema::table('traveler_tickets', function (Blueprint $table) {
            $table->foreignId('assignee_id')->nullable()->after('traveler_id')->constrained('users')->onDelete('set null');
            $table->index('assignee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traveler_tickets', function (Blueprint $table) {
            $table->dropForeign(['assignee_id']);
            $table->dropIndex(['assignee_id']);
            $table->dropColumn('assignee_id');
        });
    }
};
