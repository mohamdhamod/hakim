<?php

namespace Tests\Unit\Services;

use App\Models\Country;
use App\Models\Specialty;
use App\Services\CacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CacheService $cacheService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cacheService = new CacheService();
        Cache::flush();
    }

    public function test_specialties_are_cached(): void
    {
        Specialty::factory()->count(3)->create(['is_active' => true]);

        // First call - should hit database
        $specialties1 = $this->cacheService->getSpecialties();
        $this->assertCount(3, $specialties1);

        // Second call - should hit cache
        $this->assertTrue(Cache::has('specialties:all'));
        $specialties2 = $this->cacheService->getSpecialties();
        $this->assertCount(3, $specialties2);
    }

    public function test_countries_are_cached(): void
    {
        Country::factory()->count(5)->create(['is_active' => true]);

        $countries1 = $this->cacheService->getCountries();
        $this->assertCount(5, $countries1);

        $this->assertTrue(Cache::has('countries:all'));
        $countries2 = $this->cacheService->getCountries();
        $this->assertCount(5, $countries2);
    }

    public function test_cache_is_cleared_when_specialty_is_updated(): void
    {
        $specialty = Specialty::factory()->create(['is_active' => true]);
        
        // Cache the specialties
        $this->cacheService->getSpecialties();
        $this->assertTrue(Cache::has('specialties:all'));

        // Update specialty (should trigger observer to clear cache)
        $specialty->update(['name' => 'Updated Name']);

        // Cache should be cleared
        $this->assertFalse(Cache::has('specialties:all'));
    }

    public function test_clear_all_cache_works(): void
    {
        Specialty::factory()->create(['is_active' => true]);
        Country::factory()->create(['is_active' => true]);

        $this->cacheService->getSpecialties();
        $this->cacheService->getCountries();

        $this->assertTrue(Cache::has('specialties:all'));
        $this->assertTrue(Cache::has('countries:all'));

        $this->cacheService->clearAll();

        $this->assertFalse(Cache::has('specialties:all'));
        $this->assertFalse(Cache::has('countries:all'));
    }
}
