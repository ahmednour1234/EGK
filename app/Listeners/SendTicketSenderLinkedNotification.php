<?php

namespace App\Listeners;

use App\Events\TicketSenderLinked;
use App\Jobs\SendFcmNotificationJob;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendTicketSenderLinkedNotification implements ShouldQueue
{
    public function handle(TicketSenderLinked $event): void
    {
        try {
            $ticket = $event->ticket;
            $sender = $event->sender;

            Log::info('SendTicketSenderLinkedNotification started', [
                'ticket_id' => $ticket->id,
                'traveler_id' => $ticket->traveler_id,
                'sender_id' => $sender->id,
            ]);

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

            try {
                $notification = Notification::create([
                    'sender_id' => $ticket->traveler_id,
                    'type' => 'ticket.linked_sender',
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                    'entity' => 'ticket',
                    'entity_id' => $ticket->id,
                ]);
                Log::info('Notification created for traveler', [
                    'notification_id' => $notification->id,
                    'traveler_id' => $ticket->traveler_id,
                    'ticket_id' => $ticket->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create notification for traveler', [
                    'traveler_id' => $ticket->traveler_id,
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            try {
                $notification = Notification::create([
                    'sender_id' => $sender->id,
                    'type' => 'ticket.linked_sender',
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                    'entity' => 'ticket',
                    'entity_id' => $ticket->id,
                ]);
                Log::info('Notification created for sender', [
                    'notification_id' => $notification->id,
                    'sender_id' => $sender->id,
                    'ticket_id' => $ticket->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create notification for sender', [
                    'sender_id' => $sender->id,
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            SendFcmNotificationJob::dispatch($ticket->traveler_id, $title, $body, $data);
            Log::info('FCM job dispatched for traveler', ['traveler_id' => $ticket->traveler_id, 'ticket_id' => $ticket->id]);

            SendFcmNotificationJob::dispatch($sender->id, $title, $body, $data);
            Log::info('FCM job dispatched for sender', ['sender_id' => $sender->id, 'ticket_id' => $ticket->id]);
        } catch (\Exception $e) {
            Log::error('SendTicketSenderLinkedNotification failed', [
                'ticket_id' => $event->ticket->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
