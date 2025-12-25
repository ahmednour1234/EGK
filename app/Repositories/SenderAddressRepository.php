<?php

namespace App\Repositories;

use App\Models\SenderAddress;
use App\Repositories\Contracts\SenderAddressRepositoryInterface;

class SenderAddressRepository implements SenderAddressRepositoryInterface
{
    public function getAll(int $senderId, array $filters = [])
    {
        $query = SenderAddress::where('sender_id', $senderId);

        // Filter by type
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filter by default
        if (isset($filters['is_default'])) {
            $query->where('is_default', filter_var($filters['is_default'], FILTER_VALIDATE_BOOLEAN));
        }

        // Search in title, full_address, city, area
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('full_address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%");
            });
        }

        // Order by default first, then by created_at
        $query->orderBy('is_default', 'desc')
              ->orderBy('created_at', 'desc');

        return $query->get();
    }

    public function getById(int $id, int $senderId): ?SenderAddress
    {
        return SenderAddress::where('sender_id', $senderId)->find($id);
    }

    public function create(int $senderId, array $data): SenderAddress
    {
        $data['sender_id'] = $senderId;
        return SenderAddress::create($data);
    }

    public function update(int $id, int $senderId, array $data): SenderAddress
    {
        $address = $this->getById($id, $senderId);
        
        if (!$address) {
            throw new \Exception('Address not found');
        }

        $address->update($data);
        return $address->fresh();
    }

    public function delete(int $id, int $senderId): bool
    {
        $address = $this->getById($id, $senderId);
        
        if (!$address) {
            return false;
        }

        return $address->delete();
    }

    public function setAsDefault(int $id, int $senderId): bool
    {
        $address = $this->getById($id, $senderId);
        
        if (!$address) {
            return false;
        }

        // Unset other default addresses
        SenderAddress::where('sender_id', $senderId)
            ->where('id', '!=', $id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // Set this as default
        $address->is_default = true;
        return $address->save();
    }
}

