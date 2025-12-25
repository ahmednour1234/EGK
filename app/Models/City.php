<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'code',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

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
     * Scope to get active cities ordered.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
