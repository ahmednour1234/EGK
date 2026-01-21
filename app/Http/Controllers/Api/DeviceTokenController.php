<?php

namespace App\Http\Controllers\Api;

use App\Models\SenderDevice;
use App\Repositories\Contracts\SenderDeviceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends BaseApiController
{
    public function __construct(
        protected SenderDeviceRepositoryInterface $deviceRepository
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => ['required', 'string'],
            'fcm_token' => ['required', 'string'],
            'device_type' => ['nullable', 'string', 'in:ios,android,web'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $sender = Auth::guard('sender')->user();

        $device = $this->deviceRepository->createOrUpdate([
            'sender_id' => $sender->id,
            'device_id' => $validated['device_id'],
            'fcm_token' => $validated['fcm_token'],
            'device_type' => $validated['device_type'] ?? null,
            'device_name' => $validated['device_name'] ?? null,
        ]);

        return $this->success([
            'device_id' => $device->device_id,
            'fcm_token' => $device->fcm_token,
        ], 'Device token updated successfully');
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => ['required', 'string'],
        ]);

        $sender = Auth::guard('sender')->user();

        $device = SenderDevice::where('sender_id', $sender->id)
            ->where('device_id', $validated['device_id'])
            ->first();

        if (!$device) {
            return $this->error('Device not found', 404);
        }

        $device->update(['fcm_token' => null]);

        return $this->success(null, 'Device token removed successfully');
    }
}
