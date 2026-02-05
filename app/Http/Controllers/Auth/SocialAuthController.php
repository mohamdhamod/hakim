<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const REGISTRATION_LINK_TTL_MINUTES = 60;

    public function redirectToGoogle(Request $request)
    {
        // Capture locale when redirect is initiated from a localized page.
        // This controller is used by non-locale routes, so we must persist it ourselves.
        if ($request->filled('locale')) {
            $request->session()->put('applocale', $request->string('locale')->toString());
        }

        // Keep intended URL if provided so we can return users back after login.
        if ($request->filled('intended')) {
            $request->session()->put('url.intended', $request->string('intended')->toString());
        }

        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $locale = (string) $request->session()->get('applocale', config('app.locale'));

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('Google OAuth callback failed', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('login', ['locale' => $locale])
                ->with('auth_error', __('translation.messages.operation_failed'));
        }

        $googleId = (string) ($googleUser->getId() ?? '');
        $email = (string) ($googleUser->getEmail() ?? '');
        $name = (string) ($googleUser->getName() ?? $googleUser->getNickname() ?? '');

        if ($googleId === '' || $email === '') {
            return redirect()->route('login', ['locale' => $locale])
                ->with('auth_error', __('translation.messages.operation_failed'));
        }

        $existingUser = User::query()
            ->where('google_id', $googleId)
            ->orWhere('email', $email)
            ->first();

        $isGmail = str_ends_with(strtolower($email), '@gmail.com')
            || str_ends_with(strtolower($email), '@googlemail.com');

        // Requirement: new registrations must be through Gmail.
        if (!$existingUser && !$isGmail) {
            return redirect()->route('login', ['locale' => $locale])
                ->with('auth_error', __('translation.auth.gmail_only_registration'));
        }

        // If this account exists, sign them in.
        if ($existingUser) {
            $dirty = false;

            if (($existingUser->google_id ?? null) !== $googleId) {
                $existingUser->google_id = $googleId;
                $dirty = true;
            }

            if (!$existingUser->email_verified_at) {
                $existingUser->email_verified_at = now();
                $dirty = true;
            }

            if (($existingUser->name ?? '') === '' && $name !== '') {
                $existingUser->name = $name;
                $dirty = true;
            }

            if ($dirty) {
                $existingUser->save();
            }

            Auth::login($existingUser, true);
            $request->session()->regenerate();

            return redirect()->intended(route('home', ['locale' => $locale]));
        }

        // New Google user: show the same registration completion form used by the email-link flow.
        // We create a temporary registration token (in DB) and redirect to the signed register.complete page.
        $rawToken = Str::random(64);
        $tokenHash = hash('sha256', $rawToken);
        $expiresAt = now()->addMinutes(self::REGISTRATION_LINK_TTL_MINUTES);

        DB::table('registration_links')->updateOrInsert(
            ['email' => strtolower($email)],
            [
                'token_hash' => $tokenHash,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $request->session()->put('google_registration', [
            'email' => strtolower($email),
            'google_id' => $googleId,
            'name' => $name !== '' ? $name : Str::before($email, '@'),
        ]);

        $continueUrl = URL::temporarySignedRoute(
            'register.complete',
            $expiresAt,
            ['locale' => $locale, 'email' => strtolower($email), 'token' => $rawToken]
        );

        return redirect($continueUrl);
    }
}
