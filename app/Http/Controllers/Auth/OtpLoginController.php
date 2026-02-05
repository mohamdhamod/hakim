<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OtpLoginController extends Controller
{
    private const OTP_TTL_SECONDS = 600; // 10 minutes
    private const OTP_MAX_ATTEMPTS = 5;

    public function request(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns'],
        ]);

        $email = strtolower($data['email']);

        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [__('translation.auth.invalid_credentials')],
            ]);
        }

        $otp = (string) random_int(100000, 999999);

        Cache::put($this->otpCacheKey($email), [
            'hash' => Hash::make($otp),
            'attempts' => 0,
        ], self::OTP_TTL_SECONDS);

        Mail::to($email)->send(new LoginOtpMail($otp));

        $request->session()->put('otp_login_email', $email);

        return back()->with('status', __('translation.auth.otp_sent'));
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns'],
            'otp' => ['required', 'string', 'min:4', 'max:10'],
        ]);

        $email = strtolower($data['email']);
        $otp = preg_replace('/\s+/', '', $data['otp']);

        $payload = Cache::get($this->otpCacheKey($email));

        if (!is_array($payload) || empty($payload['hash'])) {
            throw ValidationException::withMessages([
                'otp' => [__('translation.auth.otp_invalid_or_expired')],
            ]);
        }

        $attempts = (int) ($payload['attempts'] ?? 0);
        if ($attempts >= self::OTP_MAX_ATTEMPTS) {
            Cache::forget($this->otpCacheKey($email));

            throw ValidationException::withMessages([
                'otp' => [__('translation.auth.otp_too_many_attempts')],
            ]);
        }

        if (!Hash::check($otp, $payload['hash'])) {
            $payload['attempts'] = $attempts + 1;
            Cache::put($this->otpCacheKey($email), $payload, self::OTP_TTL_SECONDS);

            throw ValidationException::withMessages([
                'otp' => [__('translation.auth.otp_invalid_or_expired')],
            ]);
        }

        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            Cache::forget($this->otpCacheKey($email));

            throw ValidationException::withMessages([
                'email' => [__('translation.auth.invalid_credentials')],
            ]);
        }

        Cache::forget($this->otpCacheKey($email));
        $request->session()->forget('otp_login_email');

        Auth::guard('web')->login($user, false);
        $request->session()->regenerate();

        return redirect()->intended(config('fortify.home'));
    }

    private function otpCacheKey(string $email): string
    {
        return 'login_otp:' . $email;
    }
}
