<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Illuminate\Support\Str;

class FirebaseRealtimeService
{
    protected $firestore;

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
            $this->firestore = $factory->createFirestore();
        } catch (\Exception $e) {
            Log::error('Firebase Firestore initialization failed: ' . $e->getMessage());
            $this->firestore = null;
        }
    }

    public function pushEvent(int $senderId, string $type, string $entity, int $entityId, array $payload = []): bool
    {
        if (!$this->firestore) {
            return false;
        }

        try {
            $database = $this->firestore->database();
            $collection = $database->collection('users')->document((string) $senderId)->collection('events');
            
            $eventData = [
                'type' => $type,
                'entity' => $entity,
                'entity_id' => $entityId,
                'payload' => $payload,
                'created_at' => now()->toIso8601String(),
            ];

            $collection->add($eventData);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Firestore push event failed: ' . $e->getMessage(), [
                'sender_id' => $senderId,
                'type' => $type,
                'entity' => $entity,
                'entity_id' => $entityId,
            ]);
            return false;
        }
    }
}
