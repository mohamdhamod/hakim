<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LocaleService
{
    /**
     * Mapping country codes to locales
     */
    private const COUNTRY_LOCALE_MAP = [
        'SA' => 'ar', // Saudi Arabia
        'EG' => 'ar', // Egypt
        'AE' => 'ar', // UAE
        'MA' => 'ar', // Morocco
        'DZ' => 'ar', // Algeria
        'TN' => 'ar', // Tunisia
        'JO' => 'ar', // Jordan
        'LB' => 'ar', // Lebanon
        'SY' => 'ar', // Syria
        'IQ' => 'ar', // Iraq
        'YE' => 'ar', // Yemen
        'KW' => 'ar', // Kuwait
        'OM' => 'ar', // Oman
        'QA' => 'ar', // Qatar
        'BH' => 'ar', // Bahrain
        'PS' => 'ar', // Palestine
        'FR' => 'fr', // France
        'BE' => 'fr', // Belgium
        'CH' => 'fr', // Switzerland
        'CA' => 'fr', // Canada (Quebec)
        'DE' => 'de', // Germany
        'AT' => 'de', // Austria
        'ES' => 'es', // Spain
        'MX' => 'es', // Mexico
        'AR' => 'es', // Argentina
        'CO' => 'es', // Colombia
        'CL' => 'es', // Chile
        'PE' => 'es', // Peru
        'IT' => 'it', // Italy
        'PT' => 'pt', // Portugal
        'BR' => 'pt', // Brazil
        'CN' => 'zh', // China
        'JP' => 'ja', // Japan
        'KR' => 'ko', // South Korea
        'RU' => 'ru', // Russia
        'TR' => 'tr', // Turkey
        'NL' => 'nl', // Netherlands
        'PL' => 'pl', // Poland
        'SE' => 'sv', // Sweden
        'NO' => 'no', // Norway
        'DK' => 'da', // Denmark
        'FI' => 'fi', // Finland
        'GR' => 'el', // Greece
        'IN' => 'hi', // India
        'PK' => 'ur', // Pakistan
        'ID' => 'id', // Indonesia
        'TH' => 'th', // Thailand
        'VN' => 'vi', // Vietnam
        'PH' => 'en', // Philippines
        'MY' => 'en', // Malaysia
        'SG' => 'en', // Singapore
    ];

    /**
     *
     * @param string|null $countryCode
     * @return string
     */
    public static function getLocaleByCountryCode(?string $countryCode): string
    {
        if (!$countryCode) {
            return config('app.fallback_locale', 'en');
        }

        $countryCode = strtoupper($countryCode);
        $locale = self::COUNTRY_LOCALE_MAP[$countryCode] ?? config('app.fallback_locale', 'en');

        $supportedLocales = array_keys(Config::get('languages', []));
        
        if (!in_array($locale, $supportedLocales, true)) {
            return config('app.fallback_locale', 'en');
        }

        return $locale;
    }

    /**
     *
     * @param \App\Models\User|null $user
     * @return string
     */
    public static function getLocaleForUser($user): string
    {
        if (!$user || !$user->country) {
            return config('app.fallback_locale', 'en');
        }

        $countryCode = $user->country->code;
        return self::getLocaleByCountryCode($countryCode);
    }

    /**
     *
     * @param string $locale
     * @return void
     */
    public static function setLocale(string $locale): void
    {
        $supportedLocales = array_keys(Config::get('languages', []));
        
        if (in_array($locale, $supportedLocales, true)) {
            App::setLocale($locale);
        }
    }

    /**
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    public static function shouldAutoDetectLocale($user): bool
    {
        return $user && !$user->locale_auto_detected && $user->country;
    }
}
