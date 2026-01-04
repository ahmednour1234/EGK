<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use App\Models\TravelerTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * @group Recent Updates
 * 
 * APIs for getting recent activity/notifications
 */
class RecentUpdatesController extends BaseApiController
{
    /**
     * Get Recent Updates
     * 
     * Get recent activity/notifications (packages and tickets updated recently).
     * For travelers: Recent updates on their tickets and packages linked to tickets
     * For senders: Recent updates on their packages
     * 
     * @queryParam limit int Number of items to return (default: 20, max: 100). Example: 20
     * @queryParam type string Filter by type (packages, tickets, all). Default: all. Example: packages
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Recent updates retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "type": "package",
     *       "title": "Package PKG-ABC123 updated",
     *       "description": "Status changed to in_transit",
     *       "updated_at": "2025-01-15T10:30:00Z"
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::guard('sender')->user();
        $limit = min(max(1, (int) $request->input('limit', 20)), 100);
        $type = $request->input('type', 'all'); // packages, tickets, all

        $updates = collect([]);

        if ($user->type === 'traveler') {
            // Get ticket IDs for this traveler
            $ticketIds = TravelerTicket::where('traveler_id', $user->id)->pluck('id')->toArray();

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
                $ticketUpdates = TravelerTicket::where('traveler_id', $user->id)
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
        } else {
            // Sender: Get recent package updates only
            $packageUpdates = Package::where('sender_id', $user->id)
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

        // Sort by updated_at desc and limit
        $updates = $updates->sortByDesc('updated_at')->take($limit)->values();

        return $this->success($updates->toArray(), 'Recent updates retrieved successfully');
    }
}

