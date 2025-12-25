<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_en',
        'question_ar',
        'answer_en',
        'answer_ar',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get question based on current locale.
     */
    public function getQuestionAttribute(): string
    {
        $locale = app()->getLocale();
        $arEnabled = Setting::get('ar_enabled', '1') === '1';
        
        if ($locale === 'ar' && $arEnabled && $this->question_ar) {
            return $this->question_ar;
        }
        
        return $this->question_en;
    }

    /**
     * Get answer based on current locale.
     */
    public function getAnswerAttribute(): string
    {
        $locale = app()->getLocale();
        $arEnabled = Setting::get('ar_enabled', '1') === '1';
        
        if ($locale === 'ar' && $arEnabled && $this->answer_ar) {
            return $this->answer_ar;
        }
        
        return $this->answer_en;
    }

    /**
     * Scope to get active FAQs ordered.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}

