<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\RecentUpdatesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Recent Updates
 * 
 * APIs for getting recent activity/notifications
 */
class RecentUpdatesController extends BaseApiController
{
    public function __construct(
        protected RecentUpdatesRepositoryInterface $recentUpdatesRepository
    ) {}

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

        if ($user->type === 'traveler') {
            $updates = $this->recentUpdatesRepository->getTravelerUpdates($user->id, $type, $limit);
        } else {
            $updates = $this->recentUpdatesRepository->getSenderUpdates($user->id, $limit);
        }

        return $this->success($updates, 'Recent updates retrieved successfully');
    }
}
