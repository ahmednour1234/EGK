<?php

namespace App\Models;

use App\Models\TravelerTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Sender extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'avatar',
        'status',
        'type',
        'is_verified',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get devices for this sender.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(SenderDevice::class);
    }

    /**
     * Get verification codes for this sender.
     */
    public function verificationCodes(): HasMany
    {
        return $this->hasMany(SenderVerificationCode::class);
    }

    /**
     * Get addresses for this sender.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(SenderAddress::class);
    }

    /**
     * Get packages for this sender.
     */
    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Get tickets for this traveler.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TravelerTicket::class, 'traveler_id');
    }

    /**
     * Check if sender is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->is_verified;
    }

    /**
     * Check if sender is blocked or banned.
     */
    public function isBlockedOrBanned(): bool
    {
        return in_array($this->status, ['blocked', 'banned']);
    }
}
