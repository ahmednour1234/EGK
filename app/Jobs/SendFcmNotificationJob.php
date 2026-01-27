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
        $tokens = array_values(array_filter(
            $this->tokens,
            fn ($t) => is_string($t) && trim($t) !== ''
        ));

        if (count($tokens) === 0) {
            return;
        }

        $title = (string) $this->title;
        $body  = (string) $this->body;

        // ✅ نفس ستايلك: data => array_filter(...)
        $data = array_filter($this->data, fn ($v) => $v !== null && $v !== '');

        $failed = [];
        $okCount = 0;

        foreach ($tokens as $token) {
            $result = $fcm->sendToToken(
                token: $token,
                title: $title,
                body: $body,
                data: $data
            );

            if (!($result['ok'] ?? false)) {
                $failed[] = [
                    'token_tail' => substr($token, -12), // عشان منسجلش التوكن كامل
                    'result' => $result,
                ];
            } else {
                $okCount++;
            }
        }

        if (!empty($failed)) {
            Log::warning('SendFcmNotificationJob v1: some tokens failed', [
                'ok' => $okCount,
                'failed' => $failed,
            ]);
        }
    }
}
