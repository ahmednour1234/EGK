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
        // departure_time / return_time في الغالب بيبقوا string "H:i:s" أو "H:i"
        $departureTime = $this->departure_time ? substr((string) $this->departure_time, 0, 5) : null;
        $arrivalTime = $this->arrival_time ? substr((string) $this->arrival_time, 0, 5) : null;
        $returnTime = $this->return_time ? substr((string) $this->return_time, 0, 5) : null;

        $departureDate = $this->departure_date?->format('Y-m-d');
        $arrivalDate = $this->arrival_date?->format('Y-m-d');
        $returnDate = $this->return_date?->format('Y-m-d');

        return [
            'id' => $this->id,
            'traveler_id' => $this->traveler_id,

            'traveler' => $this->traveler ? [
                'id' => $this->traveler->id,
                'full_name' => $this->traveler->full_name,
                'email' => $this->traveler->email,
                'phone' => $this->traveler->phone,
                'avatar' => $this->traveler->avatar,
                'status' => $this->traveler->status,
                'type' => $this->traveler->type,
                'is_verified' => $this->traveler->is_verified,
            ] : null,



            // Countries
            'from_country_id' => $this->from_country_id,
            'to_country_id' => $this->to_country_id,
            'from_country_name' => $this->fromCountry?->name,
            'to_country_name' => $this->toCountry?->name,

            // Trip Information
            'trip' => [
                'from_country_id' => $this->from_country_id,
                'to_country_id' => $this->to_country_id,

                'from_country' => $this->fromCountry ? [
                    'id' => $this->fromCountry->id,
                    'name' => $this->fromCountry->name ?? null,
                    'code' => $this->fromCountry->code ?? null,
                ] : null,
                'to_country' => $this->toCountry ? [
                    'id' => $this->toCountry->id,
                    'name' => $this->toCountry->name ?? null,
                    'code' => $this->toCountry->code ?? null,
                ] : null,

                'from_city' => $this->from_city,
                'to_city' => $this->to_city,
                'full_address' => $this->full_address,
                'landmark' => $this->landmark,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'trip_type' => $this->trip_type,
                'trip_type_label' => $this->trip_type === 'one-way' ? 'One-way' : 'Round trip',

                'departure_date' => $departureDate,
                'departure_time' => $departureTime,
                'departure_datetime' => ($departureDate && $departureTime)
                    ? ($departureDate . ' ' . $departureTime)
                    : null,

                'arrival_date' => $arrivalDate,
                'arrival_time' => $arrivalTime,
                'arrival_datetime' => ($arrivalDate && $arrivalTime)
                    ? ($arrivalDate . ' ' . $arrivalTime)
                    : null,

                'return_date' => $returnDate,
                'return_time' => $returnTime,
                'return_datetime' => ($returnDate && $returnTime)
                    ? ($returnDate . ' ' . $returnTime)
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

            // Package count (if loaded)
            'packages_count' => $this->when(isset($this->packages_count), (int) $this->packages_count),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
        ];
    }
}
