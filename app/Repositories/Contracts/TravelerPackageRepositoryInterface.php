<?php

namespace App\Repositories\Contracts;

use App\Models\Package;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TravelerPackageRepositoryInterface
{
    /**
     * Get packages linked to traveler's active tickets.
     */
    public function getPackagesWithMe(int $travelerId, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active packages (in_transit) linked to tickets, sorted by creation date.
     */
    public function getActivePackagesNow(int $travelerId, array $filters = [], int $perPage = 15): LengthAwarePaginator;
}

