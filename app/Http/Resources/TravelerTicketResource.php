<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelerTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'traveler_id' => $this->traveler_id,
            
            // Trip Information
            'trip' => [
                'from_city' => $this->from_city,
                'to_city' => $this->to_city,
                'full_address' => $this->full_address,
                'landmark' => $this->landmark,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'trip_type' => $this->trip_type,
                'trip_type_label' => $this->trip_type === 'one-way' ? 'One-way' : 'Round trip',
                'departure_date' => $this->departure_date?->format('Y-m-d'),
                'departure_time' => $this->departure_time?->format('H:i'),
                'departure_datetime' => $this->departure_date?->format('Y-m-d') . ' ' . $this->departure_time?->format('H:i'),
                'return_date' => $this->return_date?->format('Y-m-d'),
                'return_time' => $this->return_time?->format('H:i'),
                'return_datetime' => $this->return_date && $this->return_time 
                    ? $this->return_date->format('Y-m-d') . ' ' . $this->return_time->format('H:i')
                    : null,
                'transport_type' => $this->transport_type,
            ],
            
            // Travel Capacity
            'capacity' => [
                'total_weight_limit' => $this->total_weight_limit,
                'max_package_count' => $this->max_package_count,
                'acceptable_package_types' => $this->acceptable_package_types,
                'preferred_pickup_area' => $this->preferred_pickup_area,
                'preferred_delivery_area' => $this->preferred_delivery_area,
            ],
            
            // Notes & Special Conditions
            'conditions' => [
                'notes_for_senders' => $this->notes_for_senders,
                'allow_urgent_packages' => $this->allow_urgent_packages,
                'accept_only_verified_senders' => $this->accept_only_verified_senders,
            ],
            
            // Status
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
        ];
    }
}
