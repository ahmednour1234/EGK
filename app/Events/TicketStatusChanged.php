<?php

namespace App\Events;

use App\Models\TravelerTicket;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TravelerTicket $ticket,
        public ?string $oldStatus,
        public string $newStatus,
        public ?User $decidedBy = null
    ) {}
}
