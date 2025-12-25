<?php

namespace App\Repositories\Contracts;

use App\Models\SenderVerificationCode;

interface SenderVerificationCodeRepositoryInterface
{
    public function create(array $data): SenderVerificationCode;
    public function findValidCode(string $email, string $code, string $type): ?SenderVerificationCode;
    public function findValidCodeByPhone(string $phone, string $code, string $type): ?SenderVerificationCode;
    public function markAsUsed(int $id): bool;
    public function invalidateOldCodes(?int $senderId, string $email, string $type): void;
}

