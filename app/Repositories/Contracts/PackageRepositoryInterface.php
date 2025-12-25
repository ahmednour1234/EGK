<?php

namespace App\Repositories\Contracts;

use App\Models\Package;

interface PackageRepositoryInterface
{
    public function getAll(int $senderId, array $filters = []);
    public function getById(int $id, int $senderId): ?Package;
    public function create(int $senderId, array $data): Package;
    public function update(int $id, int $senderId, array $data): Package;
    public function cancel(int $id, int $senderId): bool;
}

