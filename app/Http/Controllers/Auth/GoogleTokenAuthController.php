<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Google\Client as GoogleClient;

class GoogleTokenAuthController extends Controller
{
    private const REGISTRATION_LINK_TTL_MINUTES = 60;

    /**
     * Verify Google ID token and authenticate user.
     * This method works with Google Identity Services (GIS) JavaScript SDK.
     * It's the solution for WebView environments where OAuth redirect is blocked.
     */
    public function verifyToken(Request $request): JsonResponse
    {
        $request->validate([
            'credential' => ['required', 'string'],
        ]);

        $idToken = $request->input('credential');
        $clientId = config('services.google.client_id');

        if (empty($clientId)) {
            Log::error('Google Client ID not configured');
            return response()->json([
                'success' => false,
                'message' => __('translation.messages.operation_failed'),
            ], 500);
        }

        try {
            // Verify the ID token using Google API Client
            $payload = $this->verifyIdToken($idToken, $clientId);

            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => __('translation.messages.operation_failed'),
                ], 401);
            }

            $googleId = $payload['sub'] ?? null;
            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? ($payload['given_name'] ?? '');
            $emailVerified = $payload['email_verified'] ?? false;

            if (!$googleId || !$email) {
                return response()->json([
                    'success' => false,
                    'message' => __('translation.messages.operation_failed'),
                ], 400);
            }

            // Check if email is verified by Google
            if (!$emailVerified) {
                return response()->json([
                    'success' => false,
                    'message' => __('translation.auth.email_not_verified'),
                ], 400);
            }

            // Check for Gmail requirement for new registrations
            $isGmail = str_ends_with(strtolower($email), '@gmail.com')
                || str_ends_with(strtolower($email), '@googlemail.com');

            $existingUser = User::query()
                ->where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if (!$existingUser && !$isGmail) {
                return response()->json([
                    'success' => false,
                    'message' => __('translation.auth.gmail_only_registration'),
                ], 400);
            }

            if ($existingUser) {
                // Update existing user
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

                // Login the user and redirect to intended URL.
                Auth::login($existingUser, true);
                $request->session()->regenerate();

                $locale = $request->session()->get('applocale', config('app.locale'));
                $intendedUrl = $request->session()->pull('url.intended', route('home', ['locale' => $locale]));

                return response()->json([
                    'success' => true,
                    'message' => __('translation.messages.operation_success'),
                    'redirect' => $intendedUrl,
                    'user' => [
                        'id' => $existingUser->id,
                        'name' => $existingUser->name,
                        'email' => $existingUser->email,
                    ],
                ]);
            }

            // New Google user: require completing the same registration form as OTP/email flow.
            $locale = $request->session()->get('applocale', config('app.locale'));
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

            // Prefill name/email for the completion form.
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

            return response()->json([
                'success' => true,
                'requires_registration' => true,
                'message' => __('translation.auth.continue_registration_message'),
                'redirect' => $continueUrl,
            ]);

        } catch (\Throwable $e) {
            Log::error('Google token verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('translation.messages.operation_failed'),
            ], 500);
        }
    }

    /**
     * Verify the Google ID token.
     * Uses manual JWT verification to avoid requiring google/apiclient package.
     */
    private function verifyIdToken(string $idToken, string $clientId): ?array
    {
        // First try with Google API Client if available
        if (class_exists(GoogleClient::class)) {
            try {
                $client = new GoogleClient(['client_id' => $clientId]);
                $payload = $client->verifyIdToken($idToken);
                return $payload ?: null;
            } catch (\Throwable $e) {
                Log::warning('Google API Client verification failed, trying manual verification', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Manual verification using Google's tokeninfo endpoint
        return $this->verifyTokenManually($idToken, $clientId);
    }

    /**
     * Manual token verification using Google's tokeninfo endpoint.
     * This is a fallback if google/apiclient is not installed.
     */
    private function verifyTokenManually(string $idToken, string $clientId): ?array
    {
        try {
            // Use Google's tokeninfo endpoint for verification
            $response = file_get_contents(
                'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken)
            );

            if ($response === false) {
                return null;
            }

            $payload = json_decode($response, true);

            if (!is_array($payload)) {
                return null;
            }

            // Verify the audience matches our client ID
            $aud = $payload['aud'] ?? '';
            if ($aud !== $clientId) {
                Log::warning('Google token audience mismatch', [
                    'expected' => $clientId,
                    'got' => $aud,
                ]);
                return null;
            }

            // Verify the issuer
            $iss = $payload['iss'] ?? '';
            if (!in_array($iss, ['accounts.google.com', 'https://accounts.google.com'])) {
                Log::warning('Google token issuer invalid', ['iss' => $iss]);
                return null;
            }

            // Verify token hasn't expired
            $exp = (int) ($payload['exp'] ?? 0);
            if ($exp < time()) {
                Log::warning('Google token expired', ['exp' => $exp, 'now' => time()]);
                return null;
            }

            return $payload;

        } catch (\Throwable $e) {
            Log::error('Manual Google token verification failed', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
