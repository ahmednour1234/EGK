<?php

namespace App\Http\Resources;

use App\Http\Resources\PackageTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PackageResource extends JsonResource
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
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'status_color' => $this->status_color,
            'compliance_confirmed' => $this->compliance_confirmed,
            
            // Pickup Information
            'pickup' => [
                'address_id' => $this->pickup_address_id,
                'saved_address' => $this->whenLoaded('pickupAddress', function () {
                    return [
                        'id' => $this->pickupAddress->id,
                        'title' => $this->pickupAddress->title,
                        'full_address' => $this->pickupAddress->full_address,
                    ];
                }),
                'full_address' => $this->pickup_full_address,
                'country' => $this->pickup_country,
                'city' => $this->pickup_city,
                'area' => $this->pickup_area,
                'landmark' => $this->pickup_landmark,
                'latitude' => $this->pickup_latitude,
                'longitude' => $this->pickup_longitude,
                'date' => $this->pickup_date?->format('Y-m-d'),
                'time' => $this->pickup_time?->format('H:i'),
                'datetime' => $this->pickup_date?->format('Y-m-d') . ' at ' . $this->pickup_time?->format('H:i'),
            ],
            
            // Delivery Information
            'delivery' => [
                'full_address' => $this->delivery_full_address,
                'country' => $this->delivery_country,
                'city' => $this->delivery_city,
                'area' => $this->delivery_area,
                'landmark' => $this->delivery_landmark,
                'latitude' => $this->delivery_latitude,
                'longitude' => $this->delivery_longitude,
                'date' => $this->delivery_date?->format('Y-m-d'),
                'time' => $this->delivery_time?->format('H:i'),
                'datetime' => $this->delivery_date?->format('Y-m-d') . ' at ' . $this->delivery_time?->format('H:i'),
            ],
            
            // Receiver Information
            'receiver' => [
                'name' => $this->receiver_name,
                'mobile' => $this->receiver_mobile,
                'notes' => $this->receiver_notes,
            ],
            
            // Package Information
            'package' => [
                'type' => PackageTypeResource::make($this->whenLoaded('packageType')),
                'description' => $this->description,
                'weight' => $this->weight,
                'dimensions' => [
                    'length' => $this->length,
                    'width' => $this->width,
                    'height' => $this->height,
                ],
                'dimensions_display' => $this->length && $this->width && $this->height 
                    ? "{$this->length} × {$this->width} × {$this->height} cm"
                    : null,
                'special_instructions' => $this->special_instructions,
                'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            ],
            
            'ticket_id' => $this->ticket_id,
            'ticket' => $this->whenLoaded('ticket', function () {
                return [
                    'id' => $this->ticket->id,
                    'from_city' => $this->ticket->from_city,
                    'to_city' => $this->ticket->to_city,
                    'status' => $this->ticket->status,
                    'status_label' => $this->ticket->status_label,
                ];
            }),
            
            'can_be_cancelled' => $this->canBeCancelled(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

