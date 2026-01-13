<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        'country_id',
        'description',
        'weight',
        'length',
        'width',
        'height',
        'special_instructions',
        'image',
        'status',
        'compliance_confirmed',
        'delivered_at',
        'ticket_id',
        'fees'
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
        'delivered_at' => 'datetime',
        'fees' => 'decimal:2',
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
     * Get the linked traveler ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TravelerTicket::class, 'ticket_id');
    }

    /**
     * Get the country.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get pickup datetime (pickup_date + pickup_time).
     */
    public function getPickupDatetimeAttribute(): ?Carbon
    {
        if (!$this->pickup_date || !$this->pickup_time) {
            return null;
        }

        $date = $this->pickup_date instanceof Carbon
            ? $this->pickup_date->copy()
            : Carbon::parse($this->pickup_date);

        // pickup_time is stored as time string (H:i:s)
        $timeString = is_string($this->pickup_time)
            ? $this->pickup_time
            : ($this->pickup_time instanceof Carbon
                ? $this->pickup_time->format('H:i:s')
                : Carbon::parse($this->pickup_time)->format('H:i:s'));

        return $date->setTimeFromTimeString($timeString);
    }

    /**
     * Get delivery datetime (delivery_date + delivery_time).
     */
    public function getDeliveryDatetimeAttribute(): ?Carbon
    {
        if (!$this->delivery_date || !$this->delivery_time) {
            return null;
        }

        $date = $this->delivery_date instanceof Carbon
            ? $this->delivery_date->copy()
            : Carbon::parse($this->delivery_date);

        // delivery_time is stored as time string (H:i:s)
        $timeString = is_string($this->delivery_time)
            ? $this->delivery_time
            : ($this->delivery_time instanceof Carbon
                ? $this->delivery_time->format('H:i:s')
                : Carbon::parse($this->delivery_time)->format('H:i:s'));

        return $date->setTimeFromTimeString($timeString);
    }

    /**
     * Check if package is delayed (now > delivery_datetime AND status != delivered).
     */
    public function getIsDelayedAttribute(): bool
    {
        $deliveryDatetime = $this->delivery_datetime;

        if (!$deliveryDatetime) {
            return false;
        }

        return now()->isAfter($deliveryDatetime) && $this->status !== 'delivered';
    }

    /**
     * Check if package was delivered late (status=delivered AND delivered_at > delivery_datetime).
     */
    public function getDeliveredLateAttribute(): bool
    {
        if ($this->status !== 'delivered' || !$this->delivered_at) {
            return false;
        }

        $deliveryDatetime = $this->delivery_datetime;

        if (!$deliveryDatetime) {
            return false;
        }

        $deliveredAt = $this->delivered_at instanceof Carbon
            ? $this->delivered_at
            : Carbon::parse($this->delivered_at);

        return $deliveredAt->isAfter($deliveryDatetime);
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
