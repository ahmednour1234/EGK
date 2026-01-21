<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelerTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'traveler_id',
        'assignee_id',
        'sender_id',
        'decided_by',
        'decided_at',
        'rejection_reason',

        'from_country_id',
        'to_country_id',

        'from_city',
        'to_city',
        'full_address',
        'landmark',
        'latitude',
        'longitude',
        'trip_type',
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
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
        'fees'
    ];

    protected $casts = [
        'from_country_id' => 'integer',
        'to_country_id'   => 'integer',

        'departure_date' => 'date',
        'arrival_date' => 'date',
        'return_date' => 'date',
        'decided_at' => 'datetime',
        'total_weight_limit' => 'decimal:2',
        'max_package_count' => 'integer',
        'acceptable_package_types' => 'array',
        'allow_urgent_packages' => 'boolean',
        'accept_only_verified_senders' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Traveler owner of the ticket.
     * NOTE: لو traveler_id بيروح لموديل تاني غير Sender عدّل هنا.
     */
    public function traveler(): BelongsTo
    {
        // لو عندك موديل اسمه Traveler استبدل Sender بـ Traveler
        return $this->belongsTo(Sender::class, 'traveler_id');
    }

    /**
     * Country (From).
     */
    public function fromCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'from_country_id');
    }

    /**
     * Country (To).
     */
    public function toCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'to_country_id');
    }

    /**
     * Packages linked to this ticket.
     */
    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'ticket_id');
    }

    /**
     * User assigned to this ticket.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Sender linked to this ticket.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class, 'sender_id');
    }

    /**
     * User who decided on this ticket (approved/rejected).
     */
    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    /**
     * Status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'active' => 'Active',
            'matched' => 'Matched',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Status color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'gray',
            'active' => 'info',
            'matched' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
}
