<?php

namespace App\Repositories\Contracts;

use App\Models\SenderAddress;

interface SenderAddressRepositoryInterface
{
    public function getAll(int $senderId, array $filters = []);
    public function getById(int $id, int $senderId): ?SenderAddress;
    public function create(int $senderId, array $data): SenderAddress;
    public function update(int $id, int $senderId, array $data): SenderAddress;
    public function delete(int $id, int $senderId): bool;
    public function setAsDefault(int $id, int $senderId): bool;
}

