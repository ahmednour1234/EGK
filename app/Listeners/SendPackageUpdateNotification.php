<?php

namespace App\Listeners;

use App\Events\PackageUpdated;
use App\Jobs\SendFcmNotificationJob;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPackageUpdateNotification implements ShouldQueue
{
    public function handle(PackageUpdated $event): void
    {
        $package = $event->package;
        
        $title = 'Package Updated';
        $body = "Package {$package->tracking_number} has been updated";
        
        $data = [
            'type' => 'package.updated',
            'entity' => 'package',
            'entity_id' => $package->id,
            'status' => $package->status,
            'action' => 'updated',
            'deep_link' => "app://package/{$package->id}",
        ];

        if ($package->sender_id) {
            Notification::create([
                'sender_id' => $package->sender_id,
                'type' => 'package.updated',
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
                'type' => 'package.updated',
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
