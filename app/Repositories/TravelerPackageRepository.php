<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Repositories\Contracts\TravelerPackageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class TravelerPackageRepository implements TravelerPackageRepositoryInterface
{
    public function getPackagesWithMe(int $travelerId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        // Get active ticket IDs for this traveler
        $activeTicketIds = TravelerTicket::where('traveler_id', $travelerId)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        if (empty($activeTicketIds)) {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                $perPage,
                1
            );
        }

        // Build query for packages linked to active tickets
        $query = Package::whereIn('ticket_id', $activeTicketIds)
            ->with(['packageType', 'pickupAddress', 'ticket']);

        // Apply filters
        $this->applyPackageFilters($query, $filters);

        // Order by created_at desc (newest first)
        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    public function getActivePackagesNow(int $travelerId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        // Get active ticket IDs for this traveler
        $activeTicketIds = TravelerTicket::where('traveler_id', $travelerId)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        if (empty($activeTicketIds)) {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                $perPage,
                1
            );
        }

        // Build query for in_transit packages linked to active tickets
        $query = Package::whereIn('ticket_id', $activeTicketIds)
            ->where('status', 'in_transit')
            ->with(['packageType', 'pickupAddress', 'ticket']);

        // Apply filters
        $this->applyPackageFilters($query, $filters);

        // Order by created_at asc (oldest first)
        $query->orderBy('created_at', 'asc');

        return $query->paginate($perPage);
    }

    /**
     * Apply filters to package query.
     */
    private function applyPackageFilters(Builder $query, array $filters): void
    {
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

        // Filter by created date range
        if (isset($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }
        if (isset($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }
    }
}

