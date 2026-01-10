<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $query = Country::query();

        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        } else {
            $query->where('is_active', 1);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $countries = $query->get();

        return $this->success(CountryResource::collection($countries), 'Countries retrieved successfully');
    }

    public function show(int $country): JsonResponse
    {
        $country = Country::find($country);

        if (!$country) {
            return $this->error('Country not found', 404);
        }

        return $this->success(new CountryResource($country), 'Country retrieved successfully');
    }
}

