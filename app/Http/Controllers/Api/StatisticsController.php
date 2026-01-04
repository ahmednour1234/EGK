<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @group Statistics
 * 
 * APIs for getting statistics dashboard data
 */
class StatisticsController extends BaseApiController
{
    /**
     * Get Statistics
     * 
     * Get statistics dashboard data. Returns different statistics based on user type.
     * For travelers: total/active tickets, total/delivered packages linked to tickets, assigned drivers count
     * For senders: total/delivered packages
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Statistics retrieved successfully",
     *   "data": {
     *     "tickets": {
     *       "total": 10,
     *       "active": 5
     *     },
     *     "packages": {
     *       "total": 25,
     *       "delivered": 15
     *     },
     *     "drivers": {
     *       "assigned": 3
     *     }
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        $user = Auth::guard('sender')->user();
        $statistics = [];

        if ($user->type === 'traveler') {
            // Traveler statistics
            $totalTickets = TravelerTicket::where('traveler_id', $user->id)->count();
            $activeTickets = TravelerTicket::where('traveler_id', $user->id)
                ->where('status', 'active')
                ->count();

            // Get ticket IDs for this traveler
            $ticketIds = TravelerTicket::where('traveler_id', $user->id)->pluck('id')->toArray();

            $totalPackages = 0;
            $deliveredPackages = 0;
            if (!empty($ticketIds)) {
                $totalPackages = Package::whereIn('ticket_id', $ticketIds)->count();
                $deliveredPackages = Package::whereIn('ticket_id', $ticketIds)
                    ->where('status', 'delivered')
                    ->count();
            }

            // Count unique assigned drivers/assignees
            $assignedDrivers = TravelerTicket::where('traveler_id', $user->id)
                ->whereNotNull('assignee_id')
                ->distinct('assignee_id')
                ->count('assignee_id');

            $statistics = [
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
        } else {
            // Sender statistics (packages only)
            $totalPackages = Package::where('sender_id', $user->id)->count();
            $deliveredPackages = Package::where('sender_id', $user->id)
                ->where('status', 'delivered')
                ->count();

            $statistics = [
                'packages' => [
                    'total' => $totalPackages,
                    'delivered' => $deliveredPackages,
                ],
            ];
        }

        return $this->success($statistics, 'Statistics retrieved successfully');
    }
}

