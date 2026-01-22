<?php

namespace App\Http\Requests\Api;

use App\Support\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreTravelerTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller via auth:sender middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // Trip Information
            'from_city' => ['required', 'string', 'max:255'],
            'to_city' => ['required', 'string', 'max:255'],
            'full_address' => ['required', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'trip_type' => ['required', 'string', Rule::in(['one-way', 'round-trip'])],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'departure_time' => ['required', 'date_format:H:i'],
            'arrival_date' => ['nullable', 'date', 'after_or_equal:departure_date'],
            'arrival_time' => ['nullable', 'date_format:H:i'],
            'transport_type' => ['required', 'string', 'max:255'],

            // Travel Capacity
            'total_weight_limit' => ['nullable', 'numeric', 'min:0.01'],
            'max_package_count' => ['nullable', 'integer', 'min:1'],
            'acceptable_package_types' => ['nullable', 'array'],
            'acceptable_package_types.*' => ['exists:package_types,id'],
            'preferred_pickup_area' => ['nullable', 'string', 'max:255'],
            'preferred_delivery_area' => ['nullable', 'string', 'max:255'],

            // Notes & Special Conditions
            'notes_for_senders' => ['nullable', 'string', 'max:65535'],
            'allow_urgent_packages' => ['boolean'],
            'accept_only_verified_senders' => ['boolean'],
            'from_country_id' => ['nullable', 'exists:countries,id'],
            'to_country_id' => ['nullable', 'exists:countries,id'],

            // Status (optional, defaults to draft)
            'status' => ['nullable', 'string', Rule::in(['draft', 'active'])],
        ];

        // If round-trip, require return date and time
        if ($this->input('trip_type') === 'round-trip') {
            $rules['return_date'] = ['nullable', 'date', 'after_or_equal:departure_date'];
            $rules['return_time'] = ['nullable', 'date_format:H:i'];
        } else {
            $rules['return_date'] = ['nullable', 'date'];
            $rules['return_time'] = ['nullable', 'date_format:H:i'];
        }

        return $rules;
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ApiResponse::error(
                'Validation failed'.$validator->errors()->toArray(),
                422,
                $validator->errors()->toArray()
            )
        );
    }
}
