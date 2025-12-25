<?php

namespace App\Helpers;

use App\Models\Setting;

class LanguageHelper
{
    /**
     * Check if Arabic is enabled.
     *
     * @return bool
     */
    public static function isArabicEnabled(): bool
    {
        return Setting::get('ar_enabled', '1') === '1';
    }

    /**
     * Get current locale.
     *
     * @return string
     */
    public static function getLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Set locale.
     *
     * @param string $locale
     * @return void
     */
    public static function setLocale(string $locale): void
    {
        if ($locale === 'ar' && !self::isArabicEnabled()) {
            $locale = 'en';
        }
        
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }

    /**
     * Get available locales.
     *
     * @return array
     */
    public static function getAvailableLocales(): array
    {
        $locales = ['en' => 'English'];
        
        if (self::isArabicEnabled()) {
            $locales['ar'] = 'Arabic';
        }
        
        return $locales;
    }

    /**
     * Get localized value.
     *
     * @param string $enValue
     * @param string|null $arValue
     * @return string
     */
    public static function getLocalized(string $enValue, ?string $arValue = null): string
    {
        $locale = self::getLocale();
        
        if ($locale === 'ar' && self::isArabicEnabled() && $arValue) {
            return $arValue;
        }
        
        return $enValue;
    }
}

