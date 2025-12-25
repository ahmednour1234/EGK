<?php

namespace App\Http\Requests\Api;

use App\Support\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSenderAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:home,office,warehouse,other',
            'is_default' => 'sometimes|boolean',
            'full_address' => 'required|string',
            'mobile_number' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'landmark' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
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
            'title' => [
                'description' => 'Address title (e.g., Home, Office, Warehouse)',
                'example' => 'Home',
            ],
            'type' => [
                'description' => 'Address type',
                'example' => 'home',
            ],
            'is_default' => [
                'description' => 'Set as default address',
                'example' => true,
            ],
            'full_address' => [
                'description' => 'Full address (Street, building, floor)',
                'example' => 'Hamra Plaza, Bliss Street, 4th Floor',
            ],
            'mobile_number' => [
                'description' => 'Mobile number',
                'example' => '+96170234567',
            ],
            'country' => [
                'description' => 'Country',
                'example' => 'Lebanon',
            ],
            'city' => [
                'description' => 'City',
                'example' => 'Beirut',
            ],
            'area' => [
                'description' => 'Area/District',
                'example' => 'Hamra',
            ],
            'landmark' => [
                'description' => 'Landmark (optional)',
                'example' => 'Near AUB Main Gate',
            ],
            'latitude' => [
                'description' => 'Latitude coordinate',
                'example' => 33.8938,
            ],
            'longitude' => [
                'description' => 'Longitude coordinate',
                'example' => 35.5018,
            ],
        ];
    }
}

