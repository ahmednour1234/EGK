<?php

namespace App\Repositories;

use App\Models\Package;
use App\Models\TravelerTicket;
use App\Repositories\Contracts\TravelerPackageRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TravelerPackageRepository implements TravelerPackageRepositoryInterface
{
    public function getPackagesWithMe(int $userId, string $userType, array $filters = []): Collection
    {
        // Build base query with relationships
        $query = Package::with(['packageType', 'pickupAddress', 'ticket', 'country', 'sender']);

        // If user is a sender, get their packages
        if ($userType === 'sender') {
            $query->where('sender_id', $userId);
        }
        // If user is a traveler, get packages linked to their tickets
        else {
            $activeTicketIds = TravelerTicket::where('traveler_id', $userId)
                ->pluck('id')
                ->toArray();

            if (empty($activeTicketIds)) {
                return new Collection([]);
            }

            $query->whereIn('ticket_id', $activeTicketIds);
        }

        // Apply filters
        $this->applyPackageFilters($query, $filters);

        // Order by created_at desc (newest first)
        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function getActivePackagesNow(int $userId, string $userType, array $filters = []): Collection
    {
        // Build base query for in_transit packages with relationships
        $query = Package::with(['packageType', 'pickupAddress', 'ticket', 'country', 'sender']);

        // If user is a sender, get their in_transit packages
        if ($userType === 'sender') {
            $query->where('sender_id', $userId);
        }
        // If user is a traveler, get in_transit packages linked to their active tickets
        else {
            $activeTicketIds = TravelerTicket::where('traveler_id', $userId)
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();

            if (empty($activeTicketIds)) {
                return new Collection([]);
            }

            $query->whereIn('ticket_id', $activeTicketIds);
        }

        // Apply filters
        $this->applyPackageFilters($query, $filters);

        // Order by created_at asc (oldest first)
        $query->orderBy('created_at', 'asc');

        return $query->get();
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

