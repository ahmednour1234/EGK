<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PackageType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'color',
        'description_en',
        'description_ar',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($packageType) {
            if (empty($packageType->slug)) {
                $packageType->slug = Str::slug($packageType->name_en);
            }
        });
    }

    /**
     * Get name based on current locale.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        $arEnabled = Setting::get('ar_enabled', '1') === '1';
        
        if ($locale === 'ar' && $arEnabled && $this->name_ar) {
            return $this->name_ar;
        }
        
        return $this->name_en;
    }

    /**
     * Get description based on current locale.
     */
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        $arEnabled = Setting::get('ar_enabled', '1') === '1';
        
        if ($locale === 'ar' && $arEnabled && $this->description_ar) {
            return $this->description_ar;
        }
        
        return $this->description_en;
    }


    /**
     * Scope to get active package types ordered.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}

