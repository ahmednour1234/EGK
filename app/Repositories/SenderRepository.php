<?php

namespace App\Repositories;

use App\Models\Sender;
use App\Repositories\Contracts\SenderRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class SenderRepository implements SenderRepositoryInterface
{
    public function create(array $data): Sender
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return Sender::create($data);
    }

    public function findByEmail(string $email): ?Sender
    {
        return Sender::where('email', $email)->first();
    }

    public function findByPhone(string $phone): ?Sender
    {
        return Sender::where('phone', $phone)->first();
    }

    public function findByEmailOrPhone(string $identifier): ?Sender
    {
        return Sender::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();
    }

    public function update(int $id, array $data): Sender
    {
        $sender = Sender::findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $sender->update($data);
        return $sender->fresh();
    }

    public function updateAvatar(int $id, string $avatarPath): Sender
    {
        $sender = Sender::findOrFail($id);
        $sender->avatar = $avatarPath;
        $sender->save();
        return $sender->fresh();
    }

    public function verifyEmail(int $id): bool
    {
        $sender = Sender::findOrFail($id);
        $sender->is_verified = true;
        $sender->email_verified_at = now();
        return $sender->save();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sender = Sender::findOrFail($id);
        $sender->status = $status;
        return $sender->save();
    }
}

