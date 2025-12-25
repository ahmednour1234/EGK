<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Contracts\CityRepositoryInterface;

class CityRepository implements CityRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = City::query();

        // Filter by active status
        if (isset($filters['active'])) {
            $query->where('is_active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN));
        } else {
            // Default to active only
            $query->where('is_active', true);
        }

        // Filter by code
        if (isset($filters['code'])) {
            $query->where('code', $filters['code']);
        }

        // Filter by multiple codes
        if (isset($filters['codes']) && is_array($filters['codes'])) {
            $query->whereIn('code', $filters['codes']);
        }

        // Search in name (both EN and AR)
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'order';
        $orderDirection = $filters['order_direction'] ?? 'asc';
        $query->orderBy($orderBy, $orderDirection);

        return $query->get();
    }

    public function getById(int $id)
    {
        return City::find($id);
    }
}

