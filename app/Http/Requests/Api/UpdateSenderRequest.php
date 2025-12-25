<?php

namespace App\Http\Requests\Api;

use App\Support\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateSenderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $senderId = auth('sender')->id();

        return [
            'full_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:senders,email,' . $senderId,
            'phone' => 'sometimes|string|max:255|unique:senders,phone,' . $senderId,
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
}

