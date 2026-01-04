<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PackageResource;
use App\Models\Package;
use App\Models\TravelerTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @group Traveler Packages
 *
 * APIs for managing packages linked to traveler tickets (only accessible to travelers)
 */
class TravelerPackageController extends BaseApiController
{
    /**
     * Get Packages with Me
     *
     * Get packages linked to the authenticated traveler's active tickets.
     * Only travelers (type='traveler') can access this endpoint.
     *
     * @queryParam status string Filter by status (pending_review, approved, rejected, paid, in_transit, delivered, cancelled). Example: in_transit
     * @queryParam statuses array Filter by multiple statuses. Example: ["in_transit","delivered"]
     * @queryParam package_type_id int Filter by package type ID. Example: 1
     * @queryParam ticket_id int Filter by ticket ID. Example: 1
     * @queryParam search string Search in tracking number, receiver name, or description. Example: PKG-102
     * @queryParam pickup_date_from date Filter packages with pickup date from. Example: 2025-11-01
     * @queryParam pickup_date_to date Filter packages with pickup date to. Example: 2025-11-30
     * @queryParam delivery_date_from date Filter packages with delivery date from. Example: 2025-11-01
     * @queryParam delivery_date_to date Filter packages with delivery date to. Example: 2025-11-30
     * @queryParam page int Page number for pagination. Example: 1
     * @queryParam per_page int Items per page (default: 15, max: 100). Example: 15
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Packages retrieved successfully",
     *   "data": [...],
     *   "meta": {...}
     * }
     */
    public function packagesWithMe(Request $request): JsonResponse
    {
        $traveler = Auth::guard('sender')->user();

        // Ensure user is a traveler
        if ($traveler->type !== 'traveler') {
            return $this->error('Only travelers can access this endpoint', 403);
        }

        // Get active ticket IDs for this traveler
        $activeTicketIds = TravelerTicket::where('traveler_id', $traveler->id)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        if (empty($activeTicketIds)) {
            // Return empty paginated result
            $perPage = min((int) $request->input('per_page', 15), 100);
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                $perPage,
                1
            );
            return $this->paginated($emptyPaginator, 'Packages retrieved successfully');
        }

        // Build query for packages linked to active tickets
        $query = Package::whereIn('ticket_id', $activeTicketIds)
            ->with(['packageType', 'pickupAddress', 'ticket']);

        // Apply filters
        $filters = [
            'status' => $request->input('status'),
            'statuses' => $request->input('statuses'),
            'package_type_id' => $request->input('package_type_id'),
            'ticket_id' => $request->input('ticket_id'),
            'search' => $request->input('search'),
            'pickup_date_from' => $request->input('pickup_date_from'),
            'pickup_date_to' => $request->input('pickup_date_to'),
            'delivery_date_from' => $request->input('delivery_date_from'),
            'delivery_date_to' => $request->input('delivery_date_to'),
        ];

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by multiple statuses
        if (isset($filters['statuses'])) {
            $statuses = is_string($filters['statuses'])
                ? explode(',', $filters['statuses'])
                : $filters['statuses'];
            if (is_array($statuses) && !empty($statuses)) {
                $query->whereIn('status', $statuses);
            }
        }

        // Filter by package type
        if (isset($filters['package_type_id'])) {
            $query->where('package_type_id', $filters['package_type_id']);
        }

        // Filter by ticket ID
        if (isset($filters['ticket_id'])) {
            $query->where('ticket_id', $filters['ticket_id']);
        }

        // Search
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by pickup date range
        if (isset($filters['pickup_date_from'])) {
            $query->whereDate('pickup_date', '>=', $filters['pickup_date_from']);
        }
        if (isset($filters['pickup_date_to'])) {
            $query->whereDate('pickup_date', '<=', $filters['pickup_date_to']);
        }

        // Filter by delivery date range
        if (isset($filters['delivery_date_from'])) {
            $query->whereDate('delivery_date', '>=', $filters['delivery_date_from']);
        }
        if (isset($filters['delivery_date_to'])) {
            $query->whereDate('delivery_date', '<=', $filters['delivery_date_to']);
        }

        // Order by created_at desc (newest first)
        $query->orderBy('created_at', 'desc');

        // Paginate
        $perPage = min((int) $request->input('per_page', 15), 100);
        $packages = $query->paginate($perPage);

        return $this->paginated(PackageResource::collection($packages), 'Packages retrieved successfully');
    }

    /**
     * Get Active Packages Now by Order
     *
     * Get active packages (status='in_transit') linked to tickets, sorted by creation date (oldest first).
     * Only travelers (type='traveler') can access this endpoint.
     *
     * @queryParam ticket_id int Filter by ticket ID. Example: 1
     * @queryParam search string Search in tracking number, receiver name, or description. Example: PKG-102
     * @queryParam created_from date Filter packages created from. Example: 2025-11-01
     * @queryParam created_to date Filter packages created to. Example: 2025-11-30
     * @queryParam page int Page number for pagination. Example: 1
     * @queryParam per_page int Items per page (default: 15, max: 100). Example: 15
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Active packages retrieved successfully",
     *   "data": [...],
     *   "meta": {...}
     * }
     */
    public function activePackagesNow(Request $request): JsonResponse
    {
        $traveler = Auth::guard('sender')->user();

        // Ensure user is a traveler
        if ($traveler->type !== 'traveler') {
            return $this->error('Only travelers can access this endpoint', 403);
        }

        // Get active ticket IDs for this traveler
        $activeTicketIds = TravelerTicket::where('traveler_id', $traveler->id)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        if (empty($activeTicketIds)) {
            // Return empty paginated result
            $perPage = min((int) $request->input('per_page', 15), 100);
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                $perPage,
                1
            );
            return $this->paginated($emptyPaginator, 'Active packages retrieved successfully');
        }

        // Build query for in_transit packages linked to active tickets
        $query = Package::whereIn('ticket_id', $activeTicketIds)
            ->where('status', 'in_transit')
            ->with(['packageType', 'pickupAddress', 'ticket']);

        // Apply filters
        $filters = [
            'ticket_id' => $request->input('ticket_id'),
            'search' => $request->input('search'),
            'created_from' => $request->input('created_from'),
            'created_to' => $request->input('created_to'),
        ];

        // Filter by ticket ID
        if (isset($filters['ticket_id'])) {
            $query->where('ticket_id', $filters['ticket_id']);
        }

        // Search
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by created date range
        if (isset($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }
        if (isset($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }

        // Order by created_at asc (oldest first)
        $query->orderBy('created_at', 'asc');

        // Paginate
        $perPage = min((int) $request->input('per_page', 15), 100);
        $packages = $query->paginate($perPage);

        return $this->paginated(PackageResource::collection($packages), 'Active packages retrieved successfully');
    }
}

