<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmLegacyService
{
    public function sendFcmLegacy(array $tokens, array $payload): array
    {
        $tokens = array_values(array_filter($tokens, fn ($t) => is_string($t) && trim($t) !== ''));
        if (count($tokens) === 0) {
            return ['ok' => false, 'reason' => 'empty_tokens'];
        }

        $serverKey = config('services.fcm.legacy_server_key');
        if (!is_string($serverKey) || trim($serverKey) === '') {
            Log::error('FCM legacy server key missing');
            return ['ok' => false, 'reason' => 'missing_server_key'];
        }

        // Legacy format expects: registration_ids + notification + data
        $body = [
            'registration_ids' => $tokens,
            'priority' => 'high',
            'notification' => [
                'title' => (string)($payload['title'] ?? ''),
                'body'  => (string)($payload['body'] ?? ''),
            ],
            'data' => (array)($payload['data'] ?? []),
        ];

        try {
            $res = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->post('https://fcm.googleapis.com/fcm/send', $body);

            $json = $res->json();

            if (!$res->successful()) {
                Log::error('FCM legacy send failed', [
                    'status' => $res->status(),
                    'response' => $json,
                ]);
                return ['ok' => false, 'reason' => 'http_error', 'status' => $res->status(), 'response' => $json];
            }

            return ['ok' => true, 'response' => $json];
        } catch (\Throwable $e) {
            Log::error('FCM legacy exception', ['error' => $e->getMessage()]);
            return ['ok' => false, 'reason' => 'exception', 'error' => $e->getMessage()];
        }
    }
}
