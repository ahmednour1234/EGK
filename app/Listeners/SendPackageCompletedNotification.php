<?php

namespace App\Listeners;

use App\Events\PackageCompleted;
use App\Jobs\SendFcmNotificationJob;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPackageCompletedNotification implements ShouldQueue
{
    public function handle(PackageCompleted $event): void
    {
        $package = $event->package;
        
        $title = 'Package Completed';
        $body = "Package {$package->tracking_number} has been completed";
        
        $data = [
            'type' => 'package.completed',
            'entity' => 'package',
            'entity_id' => $package->id,
            'status' => $package->status,
            'action' => 'completed',
            'deep_link' => "app://package/{$package->id}",
        ];

        if ($package->sender_id) {
            Notification::create([
                'sender_id' => $package->sender_id,
                'type' => 'package.completed',
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'entity' => 'package',
                'entity_id' => $package->id,
            ]);

            SendFcmNotificationJob::dispatch($package->sender_id, $title, $body, $data);
        }

        if ($package->ticket_id && $package->ticket) {
            Notification::create([
                'sender_id' => $package->ticket->traveler_id,
                'type' => 'package.completed',
                'title' => $title,
                'body' => $body,
                'data' => $data,
                'entity' => 'package',
                'entity_id' => $package->id,
            ]);

            SendFcmNotificationJob::dispatch($package->ticket->traveler_id, $title, $body, $data);
        }
    }
}
