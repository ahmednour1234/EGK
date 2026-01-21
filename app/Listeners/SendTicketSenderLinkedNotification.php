<?php

namespace App\Listeners;

use App\Events\TicketSenderLinked;
use App\Jobs\SendFcmNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketSenderLinkedNotification implements ShouldQueue
{
    public function handle(TicketSenderLinked $event): void
    {
        $ticket = $event->ticket;
        $sender = $event->sender;
        
        $title = 'Ticket Linked to Sender';
        $body = "Ticket #{$ticket->id} has been linked to sender";
        
        $data = [
            'type' => 'ticket.linked_sender',
            'entity' => 'ticket',
            'entity_id' => $ticket->id,
            'status' => $ticket->status,
            'action' => 'linked_sender',
            'deep_link' => "app://ticket/{$ticket->id}",
        ];

        SendFcmNotificationJob::dispatch($ticket->traveler_id, $title, $body, $data);
        SendFcmNotificationJob::dispatch($sender->id, $title, $body, $data);
    }
}
