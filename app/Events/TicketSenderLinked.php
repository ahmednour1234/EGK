<?php

namespace App\Events;

use App\Models\TravelerTicket;
use App\Models\Sender;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketSenderLinked
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public TravelerTicket $ticket,
        public Sender $sender
    ) {}
}
