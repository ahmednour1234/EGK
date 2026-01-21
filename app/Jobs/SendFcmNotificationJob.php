<?php

namespace App\Jobs;

use App\Services\FirebaseNotificationService;
use App\Services\FirebaseRealtimeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendFcmNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $senderId,
        public string $title,
        public string $body,
        public array $data = []
    ) {}

    public function handle(
        FirebaseNotificationService $notificationService,
        FirebaseRealtimeService $realtimeService
    ): void {
        try {
            $notificationService->sendToUser($this->senderId, $this->title, $this->body, $this->data);
            
            $realtimeService->pushEvent(
                $this->senderId,
                $this->data['type'] ?? 'notification',
                $this->data['entity'] ?? 'unknown',
                $this->data['entity_id'] ?? 0,
                $this->data
            );
        } catch (\Exception $e) {
            Log::error('SendFcmNotificationJob failed: ' . $e->getMessage(), [
                'sender_id' => $this->senderId,
                'title' => $this->title,
            ]);
        }
    }
}
