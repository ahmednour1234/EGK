<?php

namespace App\Repositories;

use App\Models\PackageType;
use App\Repositories\Contracts\PackageTypeRepositoryInterface;

class PackageTypeRepository implements PackageTypeRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = PackageType::query();

        // Filter by active status
        if (isset($filters['active'])) {
            $query->where('is_active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN));
        } else {
            // Default to active only
            $query->where('is_active', true);
        }

        // Filter by slug
        if (isset($filters['slug'])) {
            $query->where('slug', $filters['slug']);
        }

        // Filter by multiple slugs
        if (isset($filters['slugs']) && is_array($filters['slugs'])) {
            $query->whereIn('slug', $filters['slugs']);
        }

        // Search in name (both EN and AR) or slug
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
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
        return PackageType::find($id);
    }

    public function getBySlug(string $slug)
    {
        return PackageType::where('slug', $slug)->where('is_active', true)->first();
    }
}

