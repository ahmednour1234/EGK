<?php

namespace App\Services;

use App\Models\SenderDevice;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $credentials = config('firebase.credentials');
            if (is_string($credentials)) {
                if (file_exists($credentials)) {
                    $credentials = json_decode(file_get_contents($credentials), true);
                } else {
                    $credentials = json_decode($credentials, true);
                }
            }
            
            if (!$credentials) {
                throw new \Exception('Firebase credentials not configured');
            }
            
            $factory = (new Factory)->withServiceAccount($credentials);
            $this->messaging = $factory->createMessaging();
        } catch (\Exception $e) {
            Log::error('Firebase initialization failed: ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): array
    {
        if (!$this->messaging || empty($tokens)) {
            return ['success' => 0, 'failed' => count($tokens)];
        }

        $results = ['success' => 0, 'failed' => 0, 'invalid_tokens' => []];

        try {
            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $report = $this->messaging->sendMulticast($message, $tokens);

            $results['success'] = $report->successes()->count();
            $results['failed'] = $report->failures()->count();

            foreach ($report->failures() as $failure) {
                $token = $failure->target()->value();
                $error = $failure->error();
                
                if (in_array($error->getCode(), ['invalid-argument', 'registration-token-not-registered', 'invalid-registration-token'])) {
                    $results['invalid_tokens'][] = $token;
                    $this->removeInvalidToken($token);
                }
            }
        } catch (\Exception $e) {
            Log::error('FCM send failed: ' . $e->getMessage());
            $results['failed'] = count($tokens);
        }

        return $results;
    }

    public function sendToUser(int $senderId, string $title, string $body, array $data = []): array
    {
        $tokens = SenderDevice::where('sender_id', $senderId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->filter()
            ->toArray();

        if (empty($tokens)) {
            return ['success' => 0, 'failed' => 0, 'invalid_tokens' => []];
        }

        return $this->sendToTokens($tokens, $title, $body, $data);
    }

    protected function removeInvalidToken(string $token): void
    {
        try {
            SenderDevice::where('fcm_token', $token)->update(['fcm_token' => null]);
        } catch (\Exception $e) {
            Log::error('Failed to remove invalid token: ' . $e->getMessage());
        }
    }
}
