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
        if (!$this->messaging) {
            Log::error('Firebase notification NOT sent - FCM messaging not initialized', [
                'title' => $title,
                'token_count' => count($tokens),
            ]);
            return ['success' => 0, 'failed' => count($tokens), 'invalid_tokens' => []];
        }

        if (empty($tokens)) {
            Log::warning('Firebase notification NOT sent - No tokens provided', [
                'title' => $title,
            ]);
            return ['success' => 0, 'failed' => 0, 'invalid_tokens' => []];
        }

        $results = ['success' => 0, 'failed' => 0, 'invalid_tokens' => []];

        try {
            Log::info('Sending FCM notification to Firebase', [
                'token_count' => count($tokens),
                'title' => $title,
            ]);

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $report = $this->messaging->sendMulticast($message, $tokens);

            $results['success'] = $report->successes()->count();
            $results['failed'] = $report->failures()->count();

            if ($results['success'] > 0) {
                Log::info('Firebase notification SENT successfully', [
                    'success_count' => $results['success'],
                    'failed_count' => $results['failed'],
                    'title' => $title,
                ]);
            } else {
                Log::warning('Firebase notification NOT sent - All attempts failed', [
                    'failed_count' => $results['failed'],
                    'title' => $title,
                ]);
            }

            foreach ($report->failures() as $failure) {
                $token = $failure->target()->value();
                $error = $failure->error();
                
                Log::warning('FCM send failure', [
                    'token' => substr($token, 0, 20) . '...',
                    'error_code' => $error->getCode(),
                    'error_message' => $error->getMessage(),
                ]);
                
                if (in_array($error->getCode(), ['invalid-argument', 'registration-token-not-registered', 'invalid-registration-token'])) {
                    $results['invalid_tokens'][] = $token;
                    $this->removeInvalidToken($token);
                    Log::info('Invalid token removed', ['token' => substr($token, 0, 20) . '...']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Firebase notification NOT sent - Exception occurred', [
                'error' => $e->getMessage(),
                'title' => $title,
                'token_count' => count($tokens),
                'trace' => $e->getTraceAsString(),
            ]);
            $results['failed'] = count($tokens);
        }

        return $results;
    }

    public function sendToUser(int $senderId, string $title, string $body, array $data = []): array
    {
        Log::info('sendToUser called', [
            'sender_id' => $senderId,
            'title' => $title,
        ]);

        $tokens = SenderDevice::where('sender_id', $senderId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->filter()
            ->toArray();

        Log::info('FCM tokens retrieved', [
            'sender_id' => $senderId,
            'token_count' => count($tokens),
        ]);

        if (empty($tokens)) {
            Log::warning('Firebase notification NOT sent - No FCM tokens found for user', [
                'sender_id' => $senderId,
                'title' => $title,
            ]);
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
