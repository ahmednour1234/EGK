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
        Log::info('SendFcmNotificationJob started', [
            'sender_id' => $this->senderId,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
        ]);

        try {
            $result = $notificationService->sendToUser($this->senderId, $this->title, $this->body, $this->data);
            
            Log::info('FCM notification sent', [
                'sender_id' => $this->senderId,
                'success' => $result['success'] ?? 0,
                'failed' => $result['failed'] ?? 0,
                'invalid_tokens' => $result['invalid_tokens'] ?? [],
            ]);
            
            try {
                $realtimeService->pushEvent(
                    $this->senderId,
                    $this->data['type'] ?? 'notification',
                    $this->data['entity'] ?? 'unknown',
                    $this->data['entity_id'] ?? 0,
                    $this->data
                );
                Log::info('Realtime event pushed', ['sender_id' => $this->senderId]);
            } catch (\Exception $e) {
                Log::error('Realtime event push failed', [
                    'sender_id' => $this->senderId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('SendFcmNotificationJob failed', [
                'sender_id' => $this->senderId,
                'title' => $this->title,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
