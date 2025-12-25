<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatarUrl = null;
        if ($this->avatar) {
            // Check if avatar path already includes 'storage/' or is relative
            if (str_starts_with($this->avatar, 'storage/')) {
                $avatarUrl = asset($this->avatar);
            } else {
                $avatarUrl = asset('storage/' . $this->avatar);
            }
        }

        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $avatarUrl,
            'type' => $this->type,
            'status' => $this->status,
            'is_verified' => $this->is_verified,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

