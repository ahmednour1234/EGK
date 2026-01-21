<?php

namespace App\Listeners;

use App\Events\TicketStatusChanged;
use App\Jobs\SendFcmNotificationJob;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendTicketStatusNotification implements ShouldQueue
{
    public function handle(TicketStatusChanged $event): void
    {
        try {
            $ticket = $event->ticket;
            $travelerId = $ticket->traveler_id;
            $status = $event->newStatus;
            
            Log::info('SendTicketStatusNotification started', [
                'ticket_id' => $ticket->id,
                'traveler_id' => $travelerId,
                'sender_id' => $ticket->sender_id,
                'old_status' => $event->oldStatus,
                'new_status' => $status,
            ]);
            
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

            try {
                $notification = Notification::create([
                    'sender_id' => $travelerId,
                    'type' => 'ticket.status_changed',
                    'title' => $title,
                    'body' => $body,
                    'data' => $data,
                    'entity' => 'ticket',
                    'entity_id' => $ticket->id,
                ]);
                Log::info('Notification created for traveler', [
                    'notification_id' => $notification->id,
                    'traveler_id' => $travelerId,
                    'ticket_id' => $ticket->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create notification for traveler', [
                    'traveler_id' => $travelerId,
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            SendFcmNotificationJob::dispatch($travelerId, $title, $body, $data);
            Log::info('FCM job dispatched for traveler', ['traveler_id' => $travelerId, 'ticket_id' => $ticket->id]);

            if ($ticket->sender_id) {
                try {
                    $notification = Notification::create([
                        'sender_id' => $ticket->sender_id,
                        'type' => 'ticket.status_changed',
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
            Log::error('SendTicketStatusNotification failed', [
                'ticket_id' => $event->ticket->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
