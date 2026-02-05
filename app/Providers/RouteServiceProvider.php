<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        
        // Rate limiter for OTP requests
        RateLimiter::for('login-otp-request', function (Request $request) {
            return Limit::perMinute(3)->by($request->input('email') ?: $request->ip());
        });

        // Rate limiter for OTP verification
        RateLimiter::for('login-otp-verify', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Web routes with locale prefix
            Route::middleware('web')
                ->prefix('{locale}')
                ->where(['locale' => '[a-zA-Z]{2}'])
                ->group(base_path('routes/web.php'));

            // Dashboard routes with locale prefix
            Route::middleware(['web', 'auth', 'verified'])
                ->prefix('{locale}/dashboard')
                ->where(['locale' => '[a-zA-Z]{2}'])
                ->group(base_path('routes/dashboard.php'));

            // Social auth routes (no locale prefix needed)
            Route::middleware('web')
                ->group(base_path('routes/social.php'));

            // Root redirect to default locale
            Route::middleware('web')->get('/', function () {
                return redirect('/' . config('app.locale', 'ar'));
            });
        });
    }
}
