<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PackageResource;
use App\Repositories\Contracts\TravelerPackageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @group Traveler Packages
 *
 * APIs for managing packages linked to traveler tickets (only accessible to travelers)
 */
class TravelerPackageController extends BaseApiController
{
    public function __construct(
        protected TravelerPackageRepositoryInterface $travelerPackageRepository
    ) {}

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

        // Handle statuses as array if provided as comma-separated string
        if (isset($filters['statuses']) && is_string($filters['statuses'])) {
            $filters['statuses'] = explode(',', $filters['statuses']);
        }

        $perPage = min((int) $request->input('per_page', 15), 100);
        $packages = $this->travelerPackageRepository->getPackagesWithMe(
            $traveler->id,
            array_filter($filters, fn($value) => $value !== null),
            $perPage
        );

        return $this->paginated($packages, PackageResource::class, 'Packages retrieved successfully');
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

        $filters = [
            'ticket_id' => $request->input('ticket_id'),
            'search' => $request->input('search'),
            'created_from' => $request->input('created_from'),
            'created_to' => $request->input('created_to'),
        ];

        $perPage = min((int) $request->input('per_page', 15), 100);
        $packages = $this->travelerPackageRepository->getActivePackagesNow(
            $traveler->id,
            array_filter($filters, fn($value) => $value !== null),
            $perPage
        );

        return $this->paginated($packages, PackageResource::class, 'Active packages retrieved successfully');
    }
}
