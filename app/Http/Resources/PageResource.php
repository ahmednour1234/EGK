<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'meta_title' => ($locale === 'ar' && $arEnabled && $this->meta_title_ar) 
                ? $this->meta_title_ar 
                : $this->meta_title_en,
            'meta_description' => ($locale === 'ar' && $arEnabled && $this->meta_description_ar) 
                ? $this->meta_description_ar 
                : $this->meta_description_en,
            'is_active' => $this->is_active,
        ];
    }
}

