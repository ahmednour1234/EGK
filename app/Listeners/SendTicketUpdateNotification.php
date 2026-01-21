<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Jobs\SendFcmNotificationJob;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendTicketUpdateNotification implements ShouldQueue
{
    public function handle(TicketUpdated $event): void
    {
        try {
            $ticket = $event->ticket;
            $changedFields = $event->changedFields;

            Log::info('SendTicketUpdateNotification started', [
                'ticket_id' => $ticket->id,
                'traveler_id' => $ticket->traveler_id,
                'sender_id' => $ticket->sender_id,
                'changed_fields' => array_keys($changedFields),
            ]);

            if (isset($changedFields['status'])) {
                Log::info('Skipping notification - status change handled by TicketStatusChanged', ['ticket_id' => $ticket->id]);
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

            try {
                $notification = Notification::create([
                    'sender_id' => $ticket->traveler_id,
                    'type' => 'ticket.updated',
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

            SendFcmNotificationJob::dispatch($ticket->traveler_id, $title, $body, $data);
            Log::info('FCM job dispatched for traveler', ['traveler_id' => $ticket->traveler_id, 'ticket_id' => $ticket->id]);

            if ($ticket->sender_id) {
                try {
                    $notification = Notification::create([
                        'sender_id' => $ticket->sender_id,
                        'type' => 'ticket.updated',
                        'title' => $title,
                        'body' => $body,
                        'data' => $data,
                        'entity' => 'ticket',
                        'entity_id' => $ticket->id,
                    ]);
                    Log::info('Notification created for sender', [
                        'notification_id' => $notification->id,
                        'sender_id' => $ticket->sender_id,
                        'ticket_id' => $ticket->id,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create notification for sender', [
                        'sender_id' => $ticket->sender_id,
                        'ticket_id' => $ticket->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                SendFcmNotificationJob::dispatch($ticket->sender_id, $title, $body, $data);
                Log::info('FCM job dispatched for sender', ['sender_id' => $ticket->sender_id, 'ticket_id' => $ticket->id]);
            }
        } catch (\Exception $e) {
            Log::error('SendTicketUpdateNotification failed', [
                'ticket_id' => $event->ticket->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
