<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Specialty;
use App\Models\ConfigImage;
use App\Models\ConfigLink;
use App\Models\ConfigTitle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache durations in minutes
     */
    private const CACHE_TTL = [
        'specialties' => 1440, // 24 hours
        'countries' => 2880,   // 48 hours
        'config_images' => 720, // 12 hours
        'config_links' => 720,  // 12 hours
        'config_titles' => 720, // 12 hours
    ];

    /**
     * Get all active specialties with caching
     */
    public function getSpecialties(): Collection
    {
        return Cache::remember('specialties:all', self::CACHE_TTL['specialties'], function () {
            return Specialty::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Get all active countries with caching
     */
    public function getCountries(): Collection
    {
        return Cache::remember('countries:all', self::CACHE_TTL['countries'], function () {
            return Country::where('is_active', true)
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get config images with caching
     */
    public function getConfigImages(): Collection
    {
        return Cache::remember('config:images', self::CACHE_TTL['config_images'], function () {
            return ConfigImage::all()->keyBy('key');
        });
    }

    /**
     * Get config links with caching
     */
    public function getConfigLinks(): Collection
    {
        return Cache::remember('config:links', self::CACHE_TTL['config_links'], function () {
            return ConfigLink::all()->keyBy('key');
        });
    }

    /**
     * Get config titles with caching
     */
    public function getConfigTitles(): Collection
    {
        return Cache::remember('config:titles', self::CACHE_TTL['config_titles'], function () {
            return ConfigTitle::all()->keyBy('key');
        });
    }

    /**
     * Clear all cache
     */
    public function clearAll(): void
    {
        Cache::forget('specialties:all');
        Cache::forget('countries:all');
        Cache::forget('config:images');
        Cache::forget('config:links');
        Cache::forget('config:titles');
    }

    /**
     * Clear specialties cache
     */
    public function clearSpecialties(): void
    {
        Cache::forget('specialties:all');
    }

    /**
     * Clear countries cache
     */
    public function clearCountries(): void
    {
        Cache::forget('countries:all');
    }

    /**
     * Clear config cache
     */
    public function clearConfig(): void
    {
        Cache::forget('config:images');
        Cache::forget('config:links');
        Cache::forget('config:titles');
    }
}
