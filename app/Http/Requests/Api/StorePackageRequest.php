<?php

namespace App\Http\Requests\Api;

use App\Support\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StorePackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $senderId = auth('sender')->id();

        return [
            // Pickup Information
            'pickup_address_id' => [
                'nullable',
                Rule::exists('sender_addresses', 'id')->where('sender_id', $senderId),
            ],
            'pickup_full_address' => 'nullable|string',
            'pickup_country' => 'nullable|string|max:255',
            'pickup_city' => 'nullable|string|max:255',
            'pickup_area' => 'nullable|string|max:255',
            'pickup_landmark' => 'nullable|string',
            'pickup_latitude' => 'nullable|numeric|between:-90,90',
            'pickup_longitude' => 'nullable|numeric|between:-180,180',
            'pickup_date' => 'nullable',
            'pickup_time' => 'nullable',

            // Delivery Information
            'delivery_full_address' => 'required|string',
            'delivery_country' => 'nullable|string|max:255',
            'delivery_city' => 'required|string|max:255',
            'delivery_area' => 'nullable|string|max:255',
            'delivery_landmark' => 'nullable|string',
            'delivery_latitude' => 'nullable|numeric|between:-90,90',
            'delivery_longitude' => 'nullable|numeric|between:-180,180',
            'delivery_date' => [
                'required',
                'date',
                Rule::when($this->has('pickup_date') && $this->pickup_date, 'after_or_equal:pickup_date'),
            ],
            'delivery_time' => 'required',

            // Receiver Information
            'receiver_name' => 'required|string|max:255',
            'receiver_mobile' => 'required|string|max:255',
            'receiver_notes' => 'nullable|string',

            // Package Information
            'package_type_id' => 'required|exists:package_types,id',
            'country_id' => 'nullable|exists:countries,id',
            'description' => 'required|string',
            'weight' => 'required|numeric|min:0.01|max:1000',
            'length' => 'nullable|numeric|min:0|max:1000',
            'width' => 'nullable|numeric|min:0|max:1000',
            'height' => 'nullable|numeric|min:0|max:1000',
            'special_instructions' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',

            // Compliance
            'compliance_confirmed' => 'required|boolean|accepted',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ApiResponse::error(
                'Validation failed',
                422,
                $validator->errors()->toArray()
            )
        );
    }

    /**
     * Get body parameters for API documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'pickup_address_id' => [
                'description' => 'ID of saved pickup address (optional)',
                'example' => 1,
            ],
            'pickup_full_address' => [
                'description' => 'Full pickup address',
                'example' => 'Hamra Plaza, Bliss Street, 4th Floor',
            ],
            'pickup_city' => [
                'description' => 'Pickup city',
                'example' => 'Beirut',
            ],
            'pickup_date' => [
                'description' => 'Pickup date (YYYY-MM-DD)',
                'example' => '2025-11-03',
            ],
            'pickup_time' => [
                'description' => 'Pickup time (HH:MM)',
                'example' => '14:30',
            ],
            'delivery_full_address' => [
                'description' => 'Full delivery address',
                'example' => 'Zahle Industrial Zone, Bldg 22, 3rd Floor',
            ],
            'delivery_city' => [
                'description' => 'Delivery city',
                'example' => 'Zahle',
            ],
            'delivery_date' => [
                'description' => 'Delivery date (YYYY-MM-DD)',
                'example' => '2025-11-04',
            ],
            'delivery_time' => [
                'description' => 'Delivery time (HH:MM)',
                'example' => '15:00',
            ],
            'receiver_name' => [
                'description' => 'Receiver full name',
                'example' => 'Elie Haddad',
            ],
            'receiver_mobile' => [
                'description' => 'Receiver mobile number',
                'example' => '+96170234567',
            ],
            'package_type_id' => [
                'description' => 'Package type ID',
                'example' => 1,
            ],
            'description' => [
                'description' => 'Package description',
                'example' => 'Apple AirPods sealed box',
            ],
            'weight' => [
                'description' => 'Package weight in kg',
                'example' => 0.5,
            ],
            'compliance_confirmed' => [
                'description' => 'Confirmation of compliance with packaging guidelines',
                'example' => true,
            ],
        ];
    }
}

