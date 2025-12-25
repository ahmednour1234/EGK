<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Repositories\Contracts\FaqRepositoryInterface;

class FaqRepository implements FaqRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = Faq::query();

        // Filter by active status
        if (isset($filters['active'])) {
            $query->where('is_active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN));
        } else {
            // Default to active only
            $query->where('is_active', true);
        }

        // Order by
        $orderBy = $filters['order_by'] ?? 'order';
        $orderDirection = $filters['order_direction'] ?? 'asc';
        $query->orderBy($orderBy, $orderDirection);

        return $query->get();
    }

    public function getById(int $id)
    {
        return Faq::find($id);
    }
}

