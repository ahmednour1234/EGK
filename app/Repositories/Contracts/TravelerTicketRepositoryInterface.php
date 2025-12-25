<?php

namespace App\Repositories\Contracts;

use App\Models\TravelerTicket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TravelerTicketRepositoryInterface
{
    public function getAll(int $travelerId, array $filters = [], int $perPage = 15, bool $withTrashed = false): LengthAwarePaginator;
    public function getById(int $travelerId, int $id, bool $withTrashed = false): ?TravelerTicket;
    public function create(int $travelerId, array $data): TravelerTicket;
    public function update(int $travelerId, int $id, array $data): ?TravelerTicket;
    public function delete(int $travelerId, int $id): bool;
    public function restore(int $travelerId, int $id): bool;
    public function forceDelete(int $travelerId, int $id): bool;
}

