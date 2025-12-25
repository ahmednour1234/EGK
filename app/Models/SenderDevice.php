<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SenderDevice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'device_id',
        'fcm_token',
        'device_type',
        'device_name',
        'last_active_at',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    /**
     * Get the sender that owns the device.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }
}
