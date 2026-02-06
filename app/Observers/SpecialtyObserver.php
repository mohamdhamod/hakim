<?php

namespace App\Observers;

use App\Models\Specialty;
use App\Services\CacheService;

class SpecialtyObserver
{
    public function __construct(private CacheService $cacheService)
    {
    }

    public function created(Specialty $specialty): void
    {
        $this->cacheService->clearSpecialties();
    }

    public function updated(Specialty $specialty): void
    {
        $this->cacheService->clearSpecialties();
    }

    public function deleted(Specialty $specialty): void
    {
        $this->cacheService->clearSpecialties();
    }
}
