<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Repositories\Contracts\StatisticsRepositoryInterface;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    public function getTravelerStatistics(int $travelerId): array
    {
        $totalTickets = TravelerTicket::where('traveler_id', $travelerId)->count();
        $activeTickets = TravelerTicket::where('traveler_id', $travelerId)
            ->where('status', 'active')
            ->count();

        // Get ticket IDs for this traveler
        $ticketIds = TravelerTicket::where('traveler_id', $travelerId)->pluck('id')->toArray();

        $totalPackages = 0;
        $deliveredPackages = 0;
        if (!empty($ticketIds)) {
            $totalPackages = Package::whereIn('ticket_id', $ticketIds)->count();
            $deliveredPackages = Package::whereIn('ticket_id', $ticketIds)
                ->where('status', 'delivered')
                ->count();
        }

        // Count unique assigned drivers/assignees
        $assignedDrivers = TravelerTicket::where('traveler_id', $travelerId)
            ->whereNotNull('assignee_id')
            ->distinct('assignee_id')
            ->count('assignee_id');

        return [
            'tickets' => [
                'total' => $totalTickets,
                'active' => $activeTickets,
            ],
            'packages' => [
                'total' => $totalPackages,
                'delivered' => $deliveredPackages,
            ],
            'drivers' => [
                'assigned' => $assignedDrivers,
            ],
        ];
    }

    public function getSenderStatistics(int $senderId): array
    {
        $totalPackages = Package::where('sender_id', $senderId)->count();
        $deliveredPackages = Package::where('sender_id', $senderId)
            ->where('status', 'delivered')
            ->count();

        return [
            'packages' => [
                'total' => $totalPackages,
                'delivered' => $deliveredPackages,
            ],
        ];
    }
}

