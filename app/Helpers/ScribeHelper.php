<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class ScribeHelper
{
    /**
     * Get API base URL from settings or fallback to default.
     */
    public static function getBaseUrl(): string
    {
        try {
            if (DB::connection()->getPdo()) {
                $apiUrl = Setting::get('api_base_url');
                if ($apiUrl) {
                    return rtrim($apiUrl, '/');
                }
            }
        } catch (\Exception $e) {
            // Database not available, use default
        }
        
        return rtrim(config('app.url'), '/') . '/api';
    }

    /**
     * Get API description.
     */
    public static function getDescription(): string
    {
        try {
            if (DB::connection()->getPdo()) {
                $appName = Setting::get('app_name', config('app.name'));
                return $appName . ' API Documentation';
            }
        } catch (\Exception $e) {
            // Database not available, use default
        }
        
        return config('app.name') . ' API Documentation';
    }
}

