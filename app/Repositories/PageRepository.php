<?php

namespace App\Repositories;

use App\Models\Page;
use App\Repositories\Contracts\PageRepositoryInterface;

class PageRepository implements PageRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = Page::query();

        // Filter by slug
        if (isset($filters['slug'])) {
            $query->where('slug', $filters['slug']);
        }

        // Filter by multiple slugs
        if (isset($filters['slugs']) && is_array($filters['slugs'])) {
            $query->whereIn('slug', $filters['slugs']);
        }

        // Filter by active status
        if (isset($filters['active'])) {
            $query->where('is_active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN));
        } else {
            // Default to active only
            $query->where('is_active', true);
        }

        // Search in title or content
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%")
                  ->orWhere('content_en', 'like', "%{$search}%")
                  ->orWhere('content_ar', 'like', "%{$search}%");
            });
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'id';
        $orderDirection = $filters['order_direction'] ?? 'asc';
        $query->orderBy($orderBy, $orderDirection);

        return $query->get();
    }

    public function getBySlug(string $slug)
    {
        return Page::where('slug', $slug)->where('is_active', true)->first();
    }
}

