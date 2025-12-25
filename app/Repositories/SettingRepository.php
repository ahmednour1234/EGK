<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    public function getAll(array $filters = []): array
    {
        $query = Setting::query();

        // Filter by keys
        if (isset($filters['keys']) && is_array($filters['keys'])) {
            $query->whereIn('key', $filters['keys']);
        } elseif (isset($filters['keys']) && is_string($filters['keys'])) {
            $keys = array_map('trim', explode(',', $filters['keys']));
            $query->whereIn('key', $keys);
        }

        // Filter by type
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filter by multiple types
        if (isset($filters['types']) && is_array($filters['types'])) {
            $query->whereIn('type', $filters['types']);
        }

        // Search in key or description
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->get()->pluck('value', 'key')->toArray();
    }

    public function getByKey(string $key): ?array
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return null;
        }

        return [
            'key' => $setting->key,
            'value' => $setting->value,
            'type' => $setting->type,
            'description' => $setting->description,
        ];
    }

    public function getByKeys(array $keys): array
    {
        $settings = Setting::whereIn('key', $keys)->get();

        return $settings->mapWithKeys(function ($setting) {
            return [$setting->key => [
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
                'description' => $setting->description,
            ]];
        })->toArray();
    }
}

