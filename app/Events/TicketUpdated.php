<?php

namespace App\Events;

use App\Models\TravelerTicket;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TravelerTicket $ticket,
        public array $changedFields = [],
        public ?User $updatedBy = null
    ) {}
}
