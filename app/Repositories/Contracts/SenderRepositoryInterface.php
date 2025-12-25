<?php

namespace App\Repositories\Contracts;

use App\Models\Sender;

interface SenderRepositoryInterface
{
    public function create(array $data): Sender;
    public function findByEmail(string $email): ?Sender;
    public function findByPhone(string $phone): ?Sender;
    public function findByEmailOrPhone(string $identifier): ?Sender;
    public function update(int $id, array $data): Sender;
    public function updateAvatar(int $id, string $avatarPath): Sender;
    public function verifyEmail(int $id): bool;
    public function updateStatus(int $id, string $status): bool;
}

