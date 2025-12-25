<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'title_en',
        'title_ar',
        'content_en',
        'content_ar',
        'meta_title_en',
        'meta_title_ar',
        'meta_description_en',
        'meta_description_ar',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get title based on current locale.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        $arEnabled = Setting::get('ar_enabled', '1') === '1';
        
        if ($locale === 'ar' && $arEnabled && $this->title_ar) {
            return $this->title_ar;
        }
        
        return $this->title_en;
    }

    /**
     * Get content based on current locale.
     */
    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();
        $arEnabled = Setting::get('ar_enabled', '1') === '1';
        
        if ($locale === 'ar' && $arEnabled && $this->content_ar) {
            return $this->content_ar;
        }
        
        return $this->content_en;
    }
}

