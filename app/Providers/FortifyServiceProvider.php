<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Fortify;
// Fortify view contracts for profile pages (custom)
use Laravel\Fortify\Contracts\ViewResponse as FortifyViewResponse;

// Fortify Contracts
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Contracts\RequestPasswordResetLinkViewResponse;
use Laravel\Fortify\Contracts\ResetPasswordViewResponse;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;

// Fortify Actions
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\ResetUserPassword;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerActionResponses();
        $this->registerViewResponses();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerUserActions();
        $this->registerRateLimiters();
    }

    /**
     * Bind Fortify action responses (after login, logout, register).
     */
    private function registerActionResponses(): void
    {
        $this->app->singleton(LogoutResponse::class, \App\Http\Responses\LogoutResponse::class);
        $this->app->singleton(LoginResponse::class, \App\Http\Responses\LoginResponse::class);
        $this->app->singleton(RegisterResponse::class, \App\Http\Responses\RegisterResponse::class);
    }

    /**
     * Bind Fortify view responses.
     */
    private function registerViewResponses(): void
    {
        $this->app->singleton(LoginViewResponse::class, fn() => new class implements LoginViewResponse {
            public function toResponse($request) { return view('auth.login'); }
        });


        $this->app->singleton(RegisterViewResponse::class, fn() => new class implements RegisterViewResponse {
            public function toResponse($request) { return view('auth.register'); }
        });

        $this->app->singleton(ResetPasswordViewResponse::class, fn() => new class implements ResetPasswordViewResponse {
            public function toResponse($request) { return view('auth.passwords.reset', ['request' => $request]); }
        });

        $this->app->singleton(RequestPasswordResetLinkViewResponse::class, fn() => new class implements RequestPasswordResetLinkViewResponse {
            public function toResponse($request) { return view('auth.passwords.email'); }
        });

        $this->app->singleton(VerifyEmailViewResponse::class, fn() => new class implements VerifyEmailViewResponse {
            public function toResponse($request) { return view('auth.verify'); }
        });
    }

    /**
     * Register Fortify user actions.
     */
    private function registerUserActions(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    }

    /**
     * Register application rate limiters.
     */
    private function registerRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by(strtolower($request->email) . $request->ip());
        });

        RateLimiter::for('login-otp-request', function (Request $request) {
            return Limit::perMinute(5)->by(strtolower((string) $request->input('email')) . $request->ip());
        });

        RateLimiter::for('login-otp-verify', function (Request $request) {
            return Limit::perMinute(10)->by(strtolower((string) $request->input('email')) . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id') . $request->ip());
        });
    }
}
