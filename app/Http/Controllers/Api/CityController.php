<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CityResource;
use App\Repositories\Contracts\CityRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Cities
 * 
 * APIs for retrieving cities
 */
class CityController extends BaseApiController
{
    public function __construct(
        protected CityRepositoryInterface $cityRepository
    ) {}

    /**
     * Get All Cities
     * 
     * Get a list of all cities with advanced filtering.
     * 
     * @queryParam active boolean Filter by active status (default: true). Example: true
     * @queryParam code string Filter by city code. Example: BEY
     * @queryParam codes string Comma-separated list of codes to filter. Example: BEY,TRP,SAY
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * @queryParam search string Search in name (EN/AR) or code. Example: Beirut
     * @queryParam order_by string Order by field (default: order). Example: order
     * @queryParam order_direction string Order direction (asc, desc). Example: asc
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Cities retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Beirut",
     *       "name_en": "Beirut",
     *       "name_ar": "بيروت",
     *       "code": "BEY",
     *       "order": 1,
     *       "is_active": true
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());

        // Set locale for the request
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        $filters = [
            'active' => $request->input('active', true),
            'code' => $request->input('code'),
            'codes' => $request->input('codes') ? explode(',', $request->input('codes')) : null,
            'search' => $request->input('search'),
            'order_by' => $request->input('order_by', 'order'),
            'order_direction' => $request->input('order_direction', 'asc'),
        ];

        $cities = $this->cityRepository->getAll(array_filter($filters, fn($value) => $value !== null));

        return $this->success(CityResource::collection($cities), 'Cities retrieved successfully');
    }

    /**
     * Get Single City
     * 
     * Get a single city by ID.
     * 
     * @urlParam id int required City ID. Example: 1
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "City retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Beirut",
     *     "name_en": "Beirut",
     *     "name_ar": "بيروت",
     *     "code": "BEY",
     *     "order": 1,
     *     "is_active": true
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "City not found",
     *   "data": null
     * }
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());

        // Set locale for the request
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        $city = $this->cityRepository->getById($id);

        if (!$city) {
            return $this->error('City not found', 404);
        }

        return $this->success(new CityResource($city), 'City retrieved successfully');
    }
}

