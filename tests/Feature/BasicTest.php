<?php

namespace Tests\Feature;

use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * Test that the application bootstraps successfully.
     */
    public function test_application_boots(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test that configuration is loaded.
     */
    public function test_configuration_loaded(): void
    {
        $this->assertNotEmpty(config('app.name'));
        $this->assertIsString(config('app.name'));
    }

    /**
     * Test that database connection works.
     */
    public function test_database_connection(): void
    {
        $this->assertNotNull(\DB::connection()->getPdo());
    }
}
