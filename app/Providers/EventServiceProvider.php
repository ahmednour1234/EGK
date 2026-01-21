<?php

namespace App\Providers;

use App\Events\PackageCompleted;
use App\Events\PackageUpdated;
use App\Events\TicketSenderLinked;
use App\Events\TicketStatusChanged;
use App\Events\TicketUpdated;
use App\Listeners\SendPackageCompletedNotification;
use App\Listeners\SendPackageUpdateNotification;
use App\Listeners\SendTicketSenderLinkedNotification;
use App\Listeners\SendTicketStatusNotification;
use App\Listeners\SendTicketUpdateNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TicketStatusChanged::class => [
            SendTicketStatusNotification::class,
        ],
        TicketUpdated::class => [
            SendTicketUpdateNotification::class,
        ],
        TicketSenderLinked::class => [
            SendTicketSenderLinkedNotification::class,
        ],
        PackageUpdated::class => [
            SendPackageUpdateNotification::class,
        ],
        PackageCompleted::class => [
            SendPackageCompletedNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
