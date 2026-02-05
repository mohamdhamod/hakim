<?php

namespace Tests\Feature;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OtpLoginTest extends TestCase
{
    private function toTestUri(string $absoluteOrRelativeUrl): string
    {
        $path = parse_url($absoluteOrRelativeUrl, PHP_URL_PATH) ?? '/';
        $basePath = rtrim((string) (parse_url((string) config('app.url'), PHP_URL_PATH) ?? ''), '/');

        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        return $path === '' ? '/' : $path;
    }

    protected function setUp(): void
    {
        parent::setUp();

        // In local dev this project often uses APP_URL with /teeb/public, which makes route() generate
        // URLs containing that prefix. In the HTTP test client we want root-based URLs.
        config(['app.url' => 'http://localhost']);

        // The full project migrations currently fail on sqlite (testing) due to an unrelated duplicate column.
        // For this feature test we only need the users table.
        Schema::dropAllTables();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function test_request_otp_sends_email_and_sets_session(): void
    {
        $user = User::factory()->create();

        Mail::fake();

        $response = $this->post($this->toTestUri(route('login.otp.request', ['locale' => 'en'])), [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('otp_login_email', strtolower($user->email));

        Mail::assertSent(LoginOtpMail::class);
    }

    public function test_verify_otp_logs_in_user(): void
    {
        $user = User::factory()->create();

        Mail::fake();

        $this->post($this->toTestUri(route('login.otp.request', ['locale' => 'en'])), [
            'email' => $user->email,
        ]);

        $code = null;

        Mail::assertSent(LoginOtpMail::class, function (LoginOtpMail $mail) use (&$code) {
            $code = $mail->code;
            return true;
        });

        $response = $this->post($this->toTestUri(route('login.otp.verify', ['locale' => 'en'])), [
            'email' => $user->email,
            'otp' => $code,
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertStatus(302);
    }
}
