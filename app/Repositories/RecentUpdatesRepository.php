<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Repositories\Contracts\RecentUpdatesRepositoryInterface;
use Illuminate\Support\Collection;

class RecentUpdatesRepository implements RecentUpdatesRepositoryInterface
{
    public function getTravelerUpdates(int $travelerId, string $type = 'all', int $limit = 20): array
    {
        $updates = collect([]);

        // Get ticket IDs for this traveler
        $ticketIds = TravelerTicket::where('traveler_id', $travelerId)->pluck('id')->toArray();

        // Get recent package updates
        if (in_array($type, ['packages', 'all'])) {
            if (!empty($ticketIds)) {
                $packageUpdates = Package::whereIn('ticket_id', $ticketIds)
                    ->select('id', 'tracking_number', 'status', 'updated_at')
                    ->orderBy('updated_at', 'desc')
                    ->limit($limit)
                    ->get()
                    ->map(function ($package) {
                        return [
                            'id' => $package->id,
                            'type' => 'package',
                            'title' => "Package {$package->tracking_number} updated",
                            'description' => "Status: {$package->status_label}",
                            'updated_at' => $package->updated_at->toIso8601String(),
                            'tracking_number' => $package->tracking_number,
                            'status' => $package->status,
                            'status_label' => $package->status_label,
                        ];
                    });

                $updates = $updates->merge($packageUpdates);
            }
        }

        // Get recent ticket updates
        if (in_array($type, ['tickets', 'all'])) {
            $ticketUpdates = TravelerTicket::where('traveler_id', $travelerId)
                ->select('id', 'from_city', 'to_city', 'status', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'type' => 'ticket',
                        'title' => "Trip {$ticket->from_city} → {$ticket->to_city} updated",
                        'description' => "Status: {$ticket->status_label}",
                        'updated_at' => $ticket->updated_at->toIso8601String(),
                        'from_city' => $ticket->from_city,
                        'to_city' => $ticket->to_city,
                        'status' => $ticket->status,
                        'status_label' => $ticket->status_label,
                    ];
                });

            $updates = $updates->merge($ticketUpdates);
        }

        // Sort by updated_at desc and limit
        return $updates->sortByDesc('updated_at')->take($limit)->values()->toArray();
    }

    public function getSenderUpdates(int $senderId, int $limit = 20): array
    {
        $packageUpdates = Package::where('sender_id', $senderId)
            ->select('id', 'tracking_number', 'status', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($package) {
                return [
                    'id' => $package->id,
                    'type' => 'package',
                    'title' => "Package {$package->tracking_number} updated",
                    'description' => "Status: {$package->status_label}",
                    'updated_at' => $package->updated_at->toIso8601String(),
                    'tracking_number' => $package->tracking_number,
                    'status' => $package->status,
                    'status_label' => $package->status_label,
                ];
            })
            ->toArray();

        return $packageUpdates;
    }
}

