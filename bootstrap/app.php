<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function ($schedule) {
        // Send medical reminders daily at 9:00 AM
        $schedule->command('medical:send-reminders --type=all --days=7')
            ->dailyAt('09:00')
            ->timezone('Asia/Baghdad');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'locale' => \App\Http\Middleware\LocaleFromUrl::class,
            'auto.locale' => \App\Http\Middleware\AutoDetectUserLocale::class,
            'recaptcha' => \App\Http\Middleware\VerifyRecaptchaV3::class,
            'clinic.approved' => \App\Http\Middleware\EnsureClinicApproved::class,
            'clinic.staff' => \App\Http\Middleware\EnsureClinicStaff::class,
            'doctor.only' => \App\Http\Middleware\EnsureDoctorOnly::class,
        ]);

        $middleware->group('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\EnsureSingleSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LocaleFromUrl::class,
            \App\Http\Middleware\VerifyRecaptchaV3::class,
        ]);

        $middleware->group('api', [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\Language::class,
        ]);
    })->withExceptions(function (Exceptions $exceptions): void {
        // Handle Spatie Permission unauthorized exceptions by redirecting to a friendly page
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => __('translation.messages.no_permission_access'),
                ], 403);
            }

            return response()->view('auth.not-found', [
                'message' => __('translation.messages.no_permission_access'),
                'error_code' => 403,
                'title' => __('translation.messages.permission_denied_title'),
            ], 403);
        });
    })->create();
