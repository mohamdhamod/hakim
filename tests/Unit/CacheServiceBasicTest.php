<?php

namespace Tests\Unit;

use App\Services\CacheService;
use Tests\TestCase;

class CacheServiceBasicTest extends TestCase
{
    /**
     * Test CacheService can be instantiated
     */
    public function test_cache_service_can_be_instantiated(): void
    {
        $service = new CacheService();
        
        $this->assertInstanceOf(CacheService::class, $service);
    }

    /**
     * Test CacheService has required methods
     */
    public function test_cache_service_has_required_methods(): void
    {
        $service = new CacheService();
        
        $this->assertTrue(method_exists($service, 'getSpecialties'));
        $this->assertTrue(method_exists($service, 'getCountries'));
        $this->assertTrue(method_exists($service, 'clearAll'));
        $this->assertTrue(method_exists($service, 'clearSpecialties'));
        $this->assertTrue(method_exists($service, 'clearCountries'));
    }
}
