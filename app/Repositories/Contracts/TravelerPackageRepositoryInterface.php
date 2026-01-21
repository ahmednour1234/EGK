<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface TravelerPackageRepositoryInterface
{
    /**
     * Get packages linked to traveler's active tickets or sender's packages.
     */
    public function getPackagesWithMe(int $userId, string $userType, array $filters = []): Collection;

    /**
     * Get active packages (in_transit) linked to tickets, sorted by creation date.
     */
    public function getActivePackagesNow(int $travelerId, array $filters = []): Collection;
}

