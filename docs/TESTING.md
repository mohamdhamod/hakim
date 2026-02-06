# Testing Guide

## Overview

Hakim Clinics uses PHPUnit for testing. Tests are located in the `tests/` directory.

## Test Structure

```
tests/
├── Feature/          # Feature/Integration tests
│   ├── Auth/        # Authentication tests
│   ├── Admin/       # Admin functionality tests
│   └── Clinic/      # Clinic management tests
├── Unit/            # Unit tests
└── TestCase.php     # Base test class
```

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Feature tests only
php artisan test --testsuite=Feature

# Unit tests only
php artisan test --testsuite=Unit
```

### Run Specific Test File
```bash
php artisan test tests/Feature/Auth/OtpLoginTest.php
```

### Run with Coverage
```bash
php artisan test --coverage
```

### Run with Parallel Execution
```bash
php artisan test --parallel
```

## Test Database

Tests use SQLite in-memory database by default (configured in `phpunit.xml`):

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## Writing Tests

### Feature Test Example

```php
<?php

namespace Tests\Feature\Clinic;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_create_patient(): void
    {
        $doctor = User::factory()->create();
        $clinic = Clinic::factory()->create([
            'user_id' => $doctor->id,
            'status' => 'approved',
        ]);

        $this->actingAs($doctor);

        $response = $this->post(route('clinic.patients.store', ['locale' => 'en']), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'phone' => '1234567890',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('patients', [
            'first_name' => 'John',
            'clinic_id' => $clinic->id,
        ]);
    }
}
```

### Unit Test Example

```php
<?php

namespace Tests\Unit;

use App\Services\CacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_specialties_are_cached(): void
    {
        $cacheService = new CacheService();
        
        $result = $cacheService->getSpecialties();
        
        $this->assertTrue(Cache::has('specialties:all'));
    }
}
```

## Test Traits

### RefreshDatabase
Resets the database after each test:
```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase;
}
```

### WithFaker
Provides Faker instance for generating fake data:
```php
use Illuminate\Foundation\Testing\WithFaker;

class MyTest extends TestCase
{
    use WithFaker;
    
    public function test_example(): void
    {
        $name = $this->faker->name();
    }
}
```

## Assertions

### HTTP Response Assertions
```php
$response->assertStatus(200);
$response->assertRedirect(route('home'));
$response->assertJson(['success' => true]);
$response->assertViewIs('clinic.patients.index');
$response->assertViewHas('patient');
```

### Database Assertions
```php
$this->assertDatabaseHas('patients', ['email' => 'test@example.com']);
$this->assertDatabaseMissing('patients', ['email' => 'deleted@example.com']);
$this->assertDatabaseCount('patients', 10);
```

### Authentication Assertions
```php
$this->assertAuthenticated();
$this->assertGuest();
$this->assertAuthenticatedAs($user);
```

## Mocking

### Mail Mocking
```php
use Illuminate\Support\Facades\Mail;

Mail::fake();

// Your code that sends mail

Mail::assertQueued(LoginOtpMail::class);
Mail::assertNotQueued(WelcomeMail::class);
```

### Cache Mocking
```php
use Illuminate\Support\Facades\Cache;

Cache::shouldReceive('get')
    ->once()
    ->with('key')
    ->andReturn('value');
```

## Factories

### Creating Test Data
```php
// Create single record
$user = User::factory()->create();

// Create multiple records
$users = User::factory()->count(10)->create();

// Create with specific attributes
$user = User::factory()->create([
    'email' => 'specific@example.com',
]);

// Make (don't persist to database)
$user = User::factory()->make();
```

## Test Data Seeding

If you need to seed data for tests:

```php
public function setUp(): void
{
    parent::setUp();
    $this->seed(SpecialtiesSeeder::class);
}
```

## Continuous Integration

Tests run automatically on Git push via CI/CD pipeline:

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: php artisan test
```

## Coverage Goals

- **Unit Tests**: Aim for 80%+ coverage of Services, Models
- **Feature Tests**: Cover all critical user flows
- **Integration Tests**: Test API endpoints and database interactions

## Best Practices

1. **Test Naming**: Use descriptive test names
   ```php
   public function test_doctor_cannot_view_other_clinic_patient(): void
   ```

2. **Arrange-Act-Assert Pattern**:
   ```php
   // Arrange
   $user = User::factory()->create();
   
   // Act
   $response = $this->actingAs($user)->get('/dashboard');
   
   // Assert
   $response->assertStatus(200);
   ```

3. **One Assertion Per Test**: Focus each test on a single behavior

4. **Use Factories**: Don't manually create test data

5. **Clean Up**: Use `RefreshDatabase` to ensure test isolation

## Debugging Tests

### Run with verbose output
```bash
php artisan test --verbose
```

### Stop on first failure
```bash
php artisan test --stop-on-failure
```

### Run specific test method
```bash
php artisan test --filter test_doctor_can_create_patient
```

### Debug with dd()
```php
public function test_example(): void
{
    $response = $this->get('/api/patients');
    dd($response->json()); // Dump and die
}
```

## Common Issues

### Database not resetting
Make sure you're using `RefreshDatabase` trait

### Authentication issues
Use `actingAs($user)` before making authenticated requests

### Route not found
Ensure locale parameter is included: `route('clinic.patients.index', ['locale' => 'en'])`

## Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Test-Driven Development (TDD)](https://en.wikipedia.org/wiki/Test-driven_development)
