<?php

namespace App\Listeners;

use App\Events\TicketStatusChanged;
use App\Jobs\SendFcmNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTicketStatusNotification implements ShouldQueue
{
    public function handle(TicketStatusChanged $event): void
    {
        $ticket = $event->ticket;
        $travelerId = $ticket->traveler_id;
        $status = $event->newStatus;
        
        $action = match($status) {
            'approved' => 'approved',
            'rejected' => 'rejected',
            default => 'status_changed',
        };

        $title = match($status) {
            'approved' => 'Ticket Approved',
            'rejected' => 'Ticket Rejected',
            default => 'Ticket Status Updated',
        };

        $body = match($status) {
            'approved' => "Your ticket #{$ticket->id} has been approved",
            'rejected' => "Your ticket #{$ticket->id} has been rejected" . ($ticket->rejection_reason ? ': ' . $ticket->rejection_reason : ''),
            default => "Your ticket #{$ticket->id} status has been updated to {$status}",
        };

        $data = [
            'type' => 'ticket.status_changed',
            'entity' => 'ticket',
            'entity_id' => $ticket->id,
            'status' => $status,
            'action' => $action,
            'deep_link' => "app://ticket/{$ticket->id}",
        ];

        SendFcmNotificationJob::dispatch($travelerId, $title, $body, $data);

        if ($ticket->sender_id) {
            SendFcmNotificationJob::dispatch($ticket->sender_id, $title, $body, $data);
        }
    }
}
