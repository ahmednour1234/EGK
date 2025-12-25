<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = $request->input('locale', app()->getLocale());
        $arEnabled = Setting::get('ar_enabled', '1') === '1';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'name_ar' => ($arEnabled && $this->name_ar) ? $this->name_ar : null,
            'slug' => $this->slug,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'description_ar' => ($arEnabled && $this->description_ar) ? $this->description_ar : null,
            'color' => $this->color,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];
    }
}

