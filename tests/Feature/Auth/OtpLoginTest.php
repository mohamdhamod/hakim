<?php

namespace Tests\Feature;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_otp_can_be_requested_with_valid_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('otp.send'), [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        
        Mail::assertQueued(LoginOtpMail::class);
        
        $this->assertTrue(Cache::has('otp:test@example.com'));
    }

    public function test_otp_cannot_be_requested_with_invalid_email(): void
    {
        $response = $this->post(route('otp.send'), [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        Mail::assertNotQueued(LoginOtpMail::class);
    }

    public function test_user_can_login_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $otp = '123456';
        Cache::put('otp:test@example.com', [
            'hash' => Hash::make($otp),
            'attempts' => 0,
        ], 300);

        $response = $this->post(route('otp.verify'), [
            'email' => 'test@example.com',
            'otp' => $otp,
        ]);

        $response->assertRedirect(route('home', ['locale' => 'en']));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        Cache::put('otp:test@example.com', [
            'hash' => Hash::make('123456'),
            'attempts' => 0,
        ], 300);

        $response = $this->post(route('otp.verify'), [
            'email' => 'test@example.com',
            'otp' => '999999',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertGuest();
    }

    public function test_otp_expires_after_max_attempts(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        Cache::put('otp:test@example.com', [
            'hash' => Hash::make('123456'),
            'attempts' => 5, // Max attempts reached
        ], 300);

        $response = $this->post(route('otp.verify'), [
            'email' => 'test@example.com',
            'otp' => '123456',
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertFalse(Cache::has('otp:test@example.com'));
    }

    public function test_otp_login_rate_limited(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        // Make 6 requests (exceeds rate limit of 5 per minute)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post(route('otp.send'), [
                'email' => 'test@example.com',
            ]);
        }

        $response->assertStatus(429); // Too Many Requests
    }
}
