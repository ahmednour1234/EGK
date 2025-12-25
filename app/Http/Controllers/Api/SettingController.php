<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SettingResource;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Settings
 * 
 * APIs for retrieving application settings
 */
class SettingController extends BaseApiController
{
    public function __construct(
        protected SettingRepositoryInterface $settingRepository
    ) {}

    /**
     * Get All Settings
     * 
     * Get all application settings as key-value pairs with advanced filtering.
     * 
     * @queryParam keys string Comma-separated list of setting keys to retrieve. Example: app_name,app_url,ar_enabled
     * @queryParam type string Filter by setting type (text, url, email, number, image, file). Example: text
     * @queryParam types string Comma-separated list of types to filter. Example: text,url
     * @queryParam search string Search in key or description. Example: app
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Settings retrieved successfully",
     *   "data": {
     *     "app_name": "EGK",
     *     "app_url": "http://localhost:8009",
     *     "app_email": "info@egk.com",
     *     "app_phone": "+1 (555) 123-4567",
     *     "api_base_url": "http://localhost:8009/api",
     *     "ar_enabled": "1"
     *   }
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'keys' => $request->input('keys'),
            'type' => $request->input('type'),
            'types' => $request->input('types') ? explode(',', $request->input('types')) : null,
            'search' => $request->input('search'),
        ];

        $settings = $this->settingRepository->getAll(array_filter($filters));

        return $this->success($settings, 'Settings retrieved successfully');
    }

    /**
     * Get Setting by Key
     * 
     * Get a specific setting value by its key.
     * 
     * @urlParam key string required Setting key. Example: app_name
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Setting retrieved successfully",
     *   "data": {
     *     "key": "app_name",
     *     "value": "EGK",
     *     "type": "text",
     *     "description": "Application name"
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Setting not found",
     *   "data": null
     * }
     */
    public function show(string $key): JsonResponse
    {
        $setting = $this->settingRepository->getByKey($key);

        if (!$setting) {
            return $this->error('Setting not found', 404);
        }

        return $this->success($setting, 'Setting retrieved successfully');
    }
}

