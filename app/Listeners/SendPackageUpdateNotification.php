<?php

namespace App\Listeners;

use App\Events\PackageUpdated;
use App\Jobs\SendFcmNotificationJob;
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
            SendFcmNotificationJob::dispatch($package->sender_id, $title, $body, $data);
        }

        if ($package->ticket_id && $package->ticket) {
            SendFcmNotificationJob::dispatch($package->ticket->traveler_id, $title, $body, $data);
        }
    }
}
