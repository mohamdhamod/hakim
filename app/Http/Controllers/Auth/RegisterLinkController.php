<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterContinueMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterLinkController extends Controller
{
    private const LINK_TTL_MINUTES = 60;

    public function start(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
        ]);

        $email = strtolower($data['email']);

        $isGmail = str_ends_with($email, '@gmail.com') || str_ends_with($email, '@googlemail.com');
        if (!$isGmail) {
            throw ValidationException::withMessages([
                'email' => [__('translation.auth.gmail_only_registration')],
            ]);
        }

        // If already registered, treat as validation error (clear UX).
        if (User::query()->where('email', $email)->exists()) {
            throw ValidationException::withMessages([
                'email' => [__('translation.auth.email_already_registered')],
            ]);
        }

        $rawToken = Str::random(64);
        $tokenHash = hash('sha256', $rawToken);
        $expiresAt = now()->addMinutes(self::LINK_TTL_MINUTES);

        DB::table('registration_links')->updateOrInsert(
            ['email' => $email],
            [
                'token_hash' => $tokenHash,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $continueUrl = URL::temporarySignedRoute(
            'register.complete',
            $expiresAt,
            ['email' => $email, 'token' => $rawToken]
        );

        Mail::to($email)->send(new RegisterContinueMail($continueUrl));

        $message = __('translation.auth.continue_registration_link_sent');

        if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
            return response()->json(['message' => $message], 200);
        }

        return back()->with('status', $message);
    }

    public function complete(Request $request): RedirectResponse|\Illuminate\View\View
    {
        // Prefer redirect (nice UX) instead of Laravel's default 403 signed middleware page.
        if (!$request->hasValidSignature()) {
            return redirect()->route('register')->with('status', __('translation.auth.continue_registration_invalid_or_expired'));
        }

        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'token' => ['required', 'string', 'min:10'],
        ]);

        $email = strtolower($data['email']);
        $tokenHash = hash('sha256', $data['token']);

        $link = DB::table('registration_links')
            ->where('email', $email)
            ->where('token_hash', $tokenHash)
            ->where('expires_at', '>', now())
            ->first();

        if (!$link) {
            return redirect()->route('register')->with('status', __('translation.auth.continue_registration_invalid_or_expired'));
        }

        if (User::query()->where('email', $email)->exists()) {
            return redirect()->route('login');
        }

        $prefillName = null;
        $googleRegistration = $request->session()->get('google_registration');
        if (is_array($googleRegistration)
            && isset($googleRegistration['email'])
            && strtolower((string) $googleRegistration['email']) === $email
            && isset($googleRegistration['name'])
        ) {
            $prefillName = (string) $googleRegistration['name'];
        }

        return view('auth.register-complete', [
            'email' => $email,
            'token' => $data['token'],
            'prefill_name' => $prefillName,
        ]);
    }
}
