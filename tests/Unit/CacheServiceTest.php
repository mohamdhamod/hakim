<?php

namespace Tests\Unit;

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
        $result1 = $this->cacheService->getSpecialties();
        
        // Verify cache exists
        $this->assertTrue(Cache::has('specialties:all'));
        
        // Second call - should hit cache
        $result2 = $this->cacheService->getSpecialties();
        
        $this->assertEquals($result1, $result2);
        $this->assertCount(3, $result2);
    }

    public function test_countries_are_cached(): void
    {
        Country::factory()->count(5)->create(['is_active' => true]);

        $result1 = $this->cacheService->getCountries();
        
        $this->assertTrue(Cache::has('countries:all'));
        
        $result2 = $this->cacheService->getCountries();
        
        $this->assertEquals($result1, $result2);
        $this->assertCount(5, $result2);
    }

    public function test_cache_can_be_cleared(): void
    {
        Specialty::factory()->count(3)->create(['is_active' => true]);
        
        $this->cacheService->getSpecialties();
        $this->assertTrue(Cache::has('specialties:all'));
        
        $this->cacheService->clearSpecialties();
        $this->assertFalse(Cache::has('specialties:all'));
    }

    public function test_all_cache_can_be_cleared(): void
    {
        Specialty::factory()->count(3)->create(['is_active' => true]);
        Country::factory()->count(3)->create(['is_active' => true]);
        
        $this->cacheService->getSpecialties();
        $this->cacheService->getCountries();
        
        $this->assertTrue(Cache::has('specialties:all'));
        $this->assertTrue(Cache::has('countries:all'));
        
        $this->cacheService->clearAll();
        
        $this->assertFalse(Cache::has('specialties:all'));
        $this->assertFalse(Cache::has('countries:all'));
    }

    public function test_only_active_specialties_are_cached(): void
    {
        Specialty::factory()->count(2)->create(['is_active' => true]);
        Specialty::factory()->count(3)->create(['is_active' => false]);

        $result = $this->cacheService->getSpecialties();
        
        $this->assertCount(2, $result);
    }

    public function test_only_active_countries_are_cached(): void
    {
        Country::factory()->count(3)->create(['is_active' => true]);
        Country::factory()->count(2)->create(['is_active' => false]);

        $result = $this->cacheService->getCountries();
        
        $this->assertCount(3, $result);
    }
}
