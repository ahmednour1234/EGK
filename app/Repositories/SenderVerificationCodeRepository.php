<?php

namespace App\Repositories;

use App\Models\SenderVerificationCode;
use App\Repositories\Contracts\SenderVerificationCodeRepositoryInterface;
use Carbon\Carbon;

class SenderVerificationCodeRepository implements SenderVerificationCodeRepositoryInterface
{
    public function create(array $data): SenderVerificationCode
    {
        // Set expiration to 10 minutes from now
        if (!isset($data['expires_at'])) {
            $data['expires_at'] = Carbon::now()->addMinutes(10);
        }

        return SenderVerificationCode::create($data);
    }

    public function findValidCode(string $email, string $code, string $type): ?SenderVerificationCode
    {
        return SenderVerificationCode::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    public function findValidCodeByPhone(string $phone, string $code, string $type): ?SenderVerificationCode
    {
        return SenderVerificationCode::where('phone', $phone)
            ->where('code', $code)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    public function markAsUsed(int $id): bool
    {
        $verificationCode = SenderVerificationCode::findOrFail($id);
        $verificationCode->is_used = true;
        return $verificationCode->save();
    }

    public function invalidateOldCodes(?int $senderId, string $email, string $type): void
    {
        $query = SenderVerificationCode::where('email', $email)
            ->where('type', $type)
            ->where('is_used', false);

        if ($senderId) {
            $query->orWhere('sender_id', $senderId);
        }

        $query->update(['is_used' => true]);
    }
}

