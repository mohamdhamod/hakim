<?php

namespace App\Observers;

use App\Models\Country;
use App\Services\CacheService;

class CountryObserver
{
    public function __construct(private CacheService $cacheService)
    {
    }

    public function created(Country $country): void
    {
        $this->cacheService->clearCountries();
    }

    public function updated(Country $country): void
    {
        $this->cacheService->clearCountries();
    }

    public function deleted(Country $country): void
    {
        $this->cacheService->clearCountries();
    }
}
