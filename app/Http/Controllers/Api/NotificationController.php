<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $query = Notification::where('sender_id', $sender->id)
            ->orderBy('created_at', 'desc');

        if ($request->has('read')) {
            $read = filter_var($request->input('read'), FILTER_VALIDATE_BOOLEAN);
            if ($read) {
                $query->whereNotNull('read_at');
            } else {
                $query->whereNull('read_at');
            }
        }

        $perPage = $request->input('per_page', 20);
        $notifications = $query->paginate($perPage);

        return $this->success($notifications, 'Notifications retrieved successfully');
    }

    public function show(int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $notification = Notification::where('sender_id', $sender->id)
            ->findOrFail($id);

        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return $this->success($notification, 'Notification retrieved successfully');
    }

    public function markAsRead(int $id): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $notification = Notification::where('sender_id', $sender->id)
            ->findOrFail($id);

        $notification->update(['read_at' => now()]);

        return $this->success($notification, 'Notification marked as read');
    }

    public function markAllAsRead(): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        Notification::where('sender_id', $sender->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->success(null, 'All notifications marked as read');
    }

    public function unreadCount(): JsonResponse
    {
        $sender = Auth::guard('sender')->user();

        $count = Notification::where('sender_id', $sender->id)
            ->whereNull('read_at')
            ->count();

        return $this->success(['count' => $count], 'Unread count retrieved successfully');
    }
}
