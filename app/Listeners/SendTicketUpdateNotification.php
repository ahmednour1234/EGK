<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Jobs\SendFcmNotificationJob;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketUpdateNotification implements ShouldQueue
{
    public function handle(TicketUpdated $event): void
    {
        $ticket = $event->ticket;
        $changedFields = $event->changedFields;

        if (isset($changedFields['status'])) {
            return;
        }

        $title = 'Ticket Updated';
        $body = "Ticket #{$ticket->id} has been updated";

        $data = [
            'type' => 'ticket.updated',
            'entity' => 'ticket',
            'entity_id' => $ticket->id,
            'status' => $ticket->status,
            'action' => 'updated',
            'deep_link' => "app://ticket/{$ticket->id}",
        ];

        Notification::create([
            'sender_id' => $ticket->traveler_id,
            'type' => 'ticket.updated',
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'entity' => 'ticket',
            'entity_id' => $ticket->id,
        ]);

        SendFcmNotificationJob::dispatch($ticket->traveler_id, $title, $body, $data);

        if ($ticket->sender_id) {
            Notification::create([
                'sender_id' => $ticket->sender_id,
                'type' => 'ticket.updated',
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'entity' => 'ticket',
                'entity_id' => $ticket->id,
            ]);

            SendFcmNotificationJob::dispatch($ticket->sender_id, $title, $body, $data);
        }
    }
}
