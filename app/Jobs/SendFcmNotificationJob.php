<?php

namespace App\Jobs;

use App\Services\FcmV1Service;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendFcmNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $tokens,
        public string $title,
        public string $body,
        public array $data = [],
    ) {}

    public function handle(FcmV1Service $fcm): void
    {
        Log::info('SendFcmNotificationJob started', [
            'token_count' => count($this->tokens),
            'title' => $this->title,
            'body' => $this->body,
            'data_keys' => array_keys($this->data),
        ]);

        $tokens = array_values(array_filter(
            $this->tokens,
            fn ($t) => is_string($t) && trim($t) !== ''
        ));

        if (count($tokens) === 0) {
            Log::warning('SendFcmNotificationJob: No valid tokens found', [
                'original_token_count' => count($this->tokens),
            ]);
            return;
        }

        $title = (string) $this->title;
        $body  = (string) $this->body;

        $data = array_filter($this->data, fn ($v) => $v !== null && $v !== '');

        $failed = [];
        $okCount = 0;

        Log::info('SendFcmNotificationJob: Sending to tokens', [
            'valid_token_count' => count($tokens),
        ]);

        foreach ($tokens as $token) {
            $result = $fcm->sendToToken(
                token: $token,
                title: $title,
                body: $body,
                data: $data
            );

            if (!($result['ok'] ?? false)) {
                $failed[] = [
                    'token_tail' => substr($token, -12),
                    'result' => $result,
                ];
                Log::warning('SendFcmNotificationJob: Token failed', [
                    'token_tail' => substr($token, -12),
                    'error' => $result['error'] ?? 'unknown',
                ]);
            } else {
                $okCount++;
            }
        }

        Log::info('SendFcmNotificationJob completed', [
            'success_count' => $okCount,
            'failed_count' => count($failed),
            'total_tokens' => count($tokens),
        ]);

        if (!empty($failed)) {
            Log::warning('SendFcmNotificationJob: Some tokens failed', [
                'ok' => $okCount,
                'failed' => $failed,
            ]);
        }
    }
}
