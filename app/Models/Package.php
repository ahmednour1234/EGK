<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'tracking_number',
        'pickup_address_id',
        'pickup_full_address',
        'pickup_country',
        'pickup_city',
        'pickup_area',
        'pickup_landmark',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_date',
        'pickup_time',
        'delivery_full_address',
        'delivery_country',
        'delivery_city',
        'delivery_area',
        'delivery_landmark',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_date',
        'delivery_time',
        'receiver_name',
        'receiver_mobile',
        'receiver_notes',
        'package_type_id',
        'description',
        'weight',
        'length',
        'width',
        'height',
        'special_instructions',
        'image',
        'status',
        'compliance_confirmed',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'compliance_confirmed' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            if (empty($package->tracking_number)) {
                $package->tracking_number = 'PKG-' . strtoupper(Str::random(10));
            }
            // Set default status to pending_review
            if (empty($package->status)) {
                $package->status = 'pending_review';
            }
        });
    }

    /**
     * Get the sender that owns the package.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }

    /**
     * Get the pickup address.
     */
    public function pickupAddress(): BelongsTo
    {
        return $this->belongsTo(SenderAddress::class, 'pickup_address_id');
    }

    /**
     * Get the package type.
     */
    public function packageType(): BelongsTo
    {
        return $this->belongsTo(PackageType::class);
    }

    /**
     * Check if package can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending_review', 'approved', 'paid']);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending_review' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'paid' => 'Paid',
            'in_transit' => 'In Transit',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending_review' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'paid' => 'success',
            'in_transit' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
