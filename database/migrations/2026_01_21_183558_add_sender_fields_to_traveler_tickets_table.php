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
            $table->foreignId('sender_id')->nullable()->after('traveler_id')->constrained('senders')->onDelete('set null');
            $table->foreignId('decided_by')->nullable()->after('assignee_id')->constrained('users')->onDelete('set null');
            $table->timestamp('decided_at')->nullable()->after('decided_by');
            $table->text('rejection_reason')->nullable()->after('decided_at');
        });
    }

    public function down(): void
    {
        Schema::table('traveler_tickets', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['decided_by']);
            $table->dropColumn(['sender_id', 'decided_by', 'decided_at', 'rejection_reason']);
        });
    }
};
