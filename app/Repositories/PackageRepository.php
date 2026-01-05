<?php

namespace App\Repositories;

use App\Models\Package;
use App\Repositories\Contracts\PackageRepositoryInterface;

class PackageRepository implements PackageRepositoryInterface
{
    public function getAll(int $senderId, array $filters = [])
    {
        $query = Package::where('sender_id', $senderId)
            ->with(['packageType', 'pickupAddress']);

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by multiple statuses
        if (isset($filters['statuses']) && is_array($filters['statuses'])) {
            $query->whereIn('status', $filters['statuses']);
        }

        // Filter by package type
        if (isset($filters['package_type_id'])) {
            $query->where('package_type_id', $filters['package_type_id']);
        }

        // Search in tracking number, receiver name, description
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if (isset($filters['pickup_date_from'])) {
            $query->whereDate('pickup_date', '>=', $filters['pickup_date_from']);
        }
        if (isset($filters['pickup_date_to'])) {
            $query->whereDate('pickup_date', '<=', $filters['pickup_date_to']);
        }
        if (isset($filters['delivery_date_from'])) {
            $query->whereDate('delivery_date', '>=', $filters['delivery_date_from']);
        }
        if (isset($filters['delivery_date_to'])) {
            $query->whereDate('delivery_date', '<=', $filters['delivery_date_to']);
        }

        // Order by created_at desc (newest first)
        $query->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function getById(int $id, int $senderId): ?Package
    {
        return Package::where('sender_id', $senderId)
            ->with(['packageType', 'pickupAddress'])
            ->find($id);
    }

    public function create(int $senderId, array $data): Package
    {
        $data['sender_id'] = $senderId;
        // Ensure status is pending_review on creation
        $data['status'] = 'pending_review';
        return Package::create($data);
    }

    public function update(int $id, int $senderId, array $data): Package
    {
        $package = $this->getById($id, $senderId);
        
        if (!$package) {
            throw new \Exception('Package not found');
        }

        // Don't allow status change through update (use specific methods)
        if (isset($data['status'])) {
            unset($data['status']);
        }

        $package->update($data);
        return $package->fresh(['packageType', 'pickupAddress']);
    }

    public function cancel(int $id, int $senderId): bool
    {
        $package = $this->getById($id, $senderId);
        
        if (!$package) {
            return false;
        }

        if (!$package->canBeCancelled()) {
            throw new \Exception('Package cannot be cancelled in its current status');
        }

        $package->status = 'cancelled';
        return $package->save();
    }

    public function getActivePackage(int $senderId): ?Package
    {
        // Active packages are those that are not delivered or cancelled
        return Package::where('sender_id', $senderId)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->with(['packageType', 'pickupAddress'])
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getLastPackage(int $senderId): ?Package
    {
        // Get the most recently created package
        return Package::where('sender_id', $senderId)
            ->with(['packageType', 'pickupAddress'])
            ->orderBy('created_at', 'desc')
            ->first();
    }
}

