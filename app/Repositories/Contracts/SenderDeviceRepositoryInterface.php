<?php

namespace App\Repositories\Contracts;

use App\Models\SenderDevice;

interface SenderDeviceRepositoryInterface
{
    public function createOrUpdate(array $data): SenderDevice;
    public function findByDeviceId(int $senderId, string $deviceId): ?SenderDevice;
    public function updateFcmToken(int $id, string $fcmToken): bool;
}

