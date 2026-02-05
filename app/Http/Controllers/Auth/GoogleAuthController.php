<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        // Keep the current locale so the callback returns to the same language.
        $request->session()->put('oauth.locale', app()->getLocale());

        return Socialite::driver('google')
            ->redirectUrl(route('oauth.google.callback'))
            ->redirect();
    }

    public function callback(Request $request)
    {
        $locale = $request->session()->pull('oauth.locale', config('app.locale'));

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('oauth.google.callback', ['locale' => $locale]))
                ->user();

            $email = (string) ($googleUser->getEmail() ?? '');
            if ($email === '') {
                return redirect()->route('login')->with('auth_error', __('translation.messages.an_error_occurred'));
            }

            $user = User::query()
                ->where('email', $email)
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: $email),
                    'email' => $email,
                    'password' => Hash::make(Str::random(64)),
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                ]);

                // Default role for newly created users via OAuth.
                try {
                    $user->assignRole(RoleEnum::SUBSCRIBER);
                } catch (\Throwable $e) {
                    // If roles are not seeded yet, ignore.
                }
            } else {
                // Link Google account if not linked yet.
                if (empty($user->google_id)) {
                    $user->forceFill(['google_id' => $googleUser->getId()])->save();
                }
                if (empty($user->email_verified_at)) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                }
            }

            Auth::login($user, true);

            return redirect()->intended(route('home', ['locale' => $locale]));
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('auth_error', __('translation.messages.an_error_occurred'));
        }
    }
}
