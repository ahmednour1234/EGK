<?php

namespace App\Repositories;

use App\Models\SenderDevice;
use App\Repositories\Contracts\SenderDeviceRepositoryInterface;

class SenderDeviceRepository implements SenderDeviceRepositoryInterface
{
    public function createOrUpdate(array $data): SenderDevice
    {
        return SenderDevice::updateOrCreate(
            [
                'sender_id' => $data['sender_id'],
                'device_id' => $data['device_id'],
            ],
            [
                'fcm_token' => $data['fcm_token'] ?? null,
                'device_type' => $data['device_type'] ?? null,
                'device_name' => $data['device_name'] ?? null,
                'last_active_at' => now(),
            ]
        );
    }

    public function findByDeviceId(int $senderId, string $deviceId): ?SenderDevice
    {
        return SenderDevice::where('sender_id', $senderId)
            ->where('device_id', $deviceId)
            ->first();
    }

    public function updateFcmToken(int $id, string $fcmToken): bool
    {
        $device = SenderDevice::findOrFail($id);
        $device->fcm_token = $fcmToken;
        $device->last_active_at = now();
        return $device->save();
    }
}

