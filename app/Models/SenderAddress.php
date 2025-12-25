<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SenderAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'title',
        'type',
        'is_default',
        'full_address',
        'mobile_number',
        'country',
        'city',
        'area',
        'landmark',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the sender that owns the address.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }

    /**
     * Boot method to handle default address logic.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            // If this is set as default, unset other default addresses for this sender
            if ($address->is_default) {
                static::where('sender_id', $address->sender_id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($address) {
            // If this is set as default, unset other default addresses for this sender
            if ($address->is_default && $address->isDirty('is_default')) {
                static::where('sender_id', $address->sender_id)
                    ->where('id', '!=', $address->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });
    }
}
