<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PackageTypeResource;
use App\Repositories\Contracts\PackageTypeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Package Types
 * 
 * APIs for retrieving package types
 */
class PackageTypeController extends BaseApiController
{
    public function __construct(
        protected PackageTypeRepositoryInterface $packageTypeRepository
    ) {}

    /**
     * Get All Package Types
     * 
     * Get a list of all package types with advanced filtering.
     * 
     * @queryParam active boolean Filter by active status (default: true). Example: true
     * @queryParam slug string Filter by package type slug. Example: standard
     * @queryParam slugs string Comma-separated list of slugs to filter. Example: standard,express,overnight
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * @queryParam search string Search in name (EN/AR) or slug. Example: standard
     * @queryParam order_by string Order by field (default: order). Example: order
     * @queryParam order_direction string Order direction (asc, desc). Example: asc
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Package types retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Standard",
     *       "name_en": "Standard",
     *       "name_ar": "عادي",
     *       "slug": "standard",
     *       "description": "Standard delivery",
     *       "color": "#3B82F6",
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
            'slug' => $request->input('slug'),
            'slugs' => $request->input('slugs') ? explode(',', $request->input('slugs')) : null,
            'search' => $request->input('search'),
            'order_by' => $request->input('order_by', 'order'),
            'order_direction' => $request->input('order_direction', 'asc'),
        ];

        $packageTypes = $this->packageTypeRepository->getAll(array_filter($filters, fn($value) => $value !== null));

        return $this->success(PackageTypeResource::collection($packageTypes), 'Package types retrieved successfully');
    }

    /**
     * Get Single Package Type
     * 
     * Get a single package type by ID.
     * 
     * @urlParam id int required Package Type ID. Example: 1
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Package type retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Standard",
     *     "name_en": "Standard",
     *     "name_ar": "عادي",
     *     "slug": "standard",
     *     "description": "Standard delivery",
     *     "color": "#3B82F6",
     *     "order": 1,
     *     "is_active": true
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Package type not found",
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

        $packageType = $this->packageTypeRepository->getById($id);

        if (!$packageType) {
            return $this->error('Package type not found', 404);
        }

        return $this->success(new PackageTypeResource($packageType), 'Package type retrieved successfully');
    }

    /**
     * Get Package Type by Slug
     * 
     * Get a single package type by its slug.
     * 
     * @urlParam slug string required Package type slug. Example: standard
     * @queryParam locale string Language locale (en, ar). Defaults to application locale. Example: en
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Package type retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Standard",
     *     "name_en": "Standard",
     *     "name_ar": "عادي",
     *     "slug": "standard",
     *     "description": "Standard delivery",
     *     "color": "#3B82F6",
     *     "order": 1,
     *     "is_active": true
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Package type not found",
     *   "data": null
     * }
     */
    public function showBySlug(string $slug, Request $request): JsonResponse
    {
        $locale = $request->input('locale', app()->getLocale());

        // Set locale for the request
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        $packageType = $this->packageTypeRepository->getBySlug($slug);

        if (!$packageType) {
            return $this->error('Package type not found', 404);
        }

        return $this->success(new PackageTypeResource($packageType), 'Package type retrieved successfully');
    }
}

