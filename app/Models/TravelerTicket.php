<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelerTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'traveler_id',
        'from_city',
        'to_city',
        'full_address',
        'landmark',
        'latitude',
        'longitude',
        'trip_type',
        'departure_date',
        'departure_time',
        'return_date',
        'return_time',
        'transport_type',
        'total_weight_limit',
        'max_package_count',
        'acceptable_package_types',
        'preferred_pickup_area',
        'preferred_delivery_area',
        'notes_for_senders',
        'allow_urgent_packages',
        'accept_only_verified_senders',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'total_weight_limit' => 'decimal:2',
        'max_package_count' => 'integer',
        'acceptable_package_types' => 'array',
        'allow_urgent_packages' => 'boolean',
        'accept_only_verified_senders' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Get the traveler that owns the ticket.
     */
    public function traveler(): BelongsTo
    {
        return $this->belongsTo(Sender::class, 'traveler_id');
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'active' => 'Active',
            'matched' => 'Matched',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'active' => 'info',
            'matched' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
}
