<?php

namespace App\Observers;

use App\Events\TicketStatusChanged;
use App\Events\TicketUpdated;
use App\Models\TravelerTicket;
use Illuminate\Support\Facades\Auth;

class TravelerTicketObserver
{
    /**
     * Handle the TravelerTicket "created" event.
     */
    public function created(TravelerTicket $travelerTicket): void
    {
        //
    }

    /**
     * Handle the TravelerTicket "updated" event.
     */
    public function updated(TravelerTicket $travelerTicket): void
    {
        $original = $travelerTicket->getOriginal();
        $changedFields = [];
        
        foreach ($travelerTicket->getDirty() as $key => $value) {
            $changedFields[$key] = [
                'old' => $original[$key] ?? null,
                'new' => $value,
            ];
        }

        if (isset($changedFields['status'])) {
            TicketStatusChanged::dispatch(
                $travelerTicket,
                $changedFields['status']['old'],
                $changedFields['status']['new'],
                Auth::user()
            );
        }

        $nonStatusChanges = array_filter($changedFields, fn($key) => $key !== 'status', ARRAY_FILTER_USE_KEY);
        
        if (!empty($nonStatusChanges)) {
            TicketUpdated::dispatch(
                $travelerTicket,
                $nonStatusChanges,
                Auth::user()
            );
        }
    }

    /**
     * Handle the TravelerTicket "deleted" event.
     */
    public function deleted(TravelerTicket $travelerTicket): void
    {
        //
    }

    /**
     * Handle the TravelerTicket "restored" event.
     */
    public function restored(TravelerTicket $travelerTicket): void
    {
        //
    }

    /**
     * Handle the TravelerTicket "force deleted" event.
     */
    public function forceDeleted(TravelerTicket $travelerTicket): void
    {
        //
    }
}
