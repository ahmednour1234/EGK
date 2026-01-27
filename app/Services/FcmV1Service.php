<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmV1Service
{
    protected function getAccessToken(): ?string
    {
        $path = config('services.firebase.service_account');

        if (!is_string($path) || trim($path) === '' || !file_exists($path)) {
            Log::error('FCM v1: service account file missing', ['path' => $path]);
            return null;
        }

        $json = json_decode(file_get_contents($path), true);

        if (!is_array($json)) {
            Log::error('FCM v1: invalid service account json');
            return null;
        }

        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            $json
        );

        $token = $credentials->fetchAuthToken();

        return $token['access_token'] ?? null;
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        $token = trim($token);
        if ($token === '') {
            return ['ok' => false, 'error' => 'empty_token'];
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return ['ok' => false, 'error' => 'no_access_token'];
        }

        $projectId = config('services.firebase.project_id');
        if (!is_string($projectId) || trim($projectId) === '') {
            return ['ok' => false, 'error' => 'missing_project_id'];
        }

        // FCM v1 requires data values as strings
        $data = array_filter($data, fn ($v) => $v !== null && $v !== '');
        $data = array_map(fn ($v) => is_string($v) ? $v : (string) $v, $data);

        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'data' => $data,
            ],
        ];

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $resp = Http::withToken($accessToken)
            ->acceptJson()
            ->asJson()
            ->post($url, $payload);

        if ($resp->successful()) {
            return ['ok' => true, 'response' => $resp->json()];
        }

        return [
            'ok' => false,
            'status' => $resp->status(),
            'response' => $resp->json(),
            'raw' => $resp->body(),
        ];
    }
}
