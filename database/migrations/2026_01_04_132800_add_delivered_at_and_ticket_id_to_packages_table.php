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
            $table->timestamp('delivered_at')->nullable()->after('status');
            $table->foreignId('ticket_id')->nullable()->after('delivered_at')->constrained('traveler_tickets')->onDelete('set null');
            
            // Indexes for KPI queries
            $table->index('status');
            $table->index('delivered_at');
            $table->index(['status', 'delivered_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropIndex(['status', 'delivered_at']);
            $table->dropIndex(['delivered_at']);
            $table->dropIndex(['status']);
            $table->dropColumn(['delivered_at', 'ticket_id']);
        });
    }
};
