<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\GoogleTokenAuthController;

// Social auth routes are intentionally NOT locale-prefixed.
// This keeps Google OAuth callback URL stable.
Route::middleware(['guest'])->group(function () {
    Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])
        ->name('oauth.google.redirect');

    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
        ->name('oauth.google.callback');

    // Google Identity Services (GIS) token verification endpoint
    // This works in WebView environments where OAuth redirect is blocked
    Route::post('/auth/google/token', [GoogleTokenAuthController::class, 'verifyToken'])
        ->name('oauth.google.token');
});
