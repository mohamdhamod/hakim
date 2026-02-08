<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;

class VerifyRecaptchaV3
{
    public function handle(Request $request, Closure $next)
    {
        $secret = (string) config('services.recaptcha_v3.secret_key');
        $minScore = (float) config('services.recaptcha_v3.min_score', 0.5);

        // Skip verification if no secret key is configured
        if ($secret === '') {
            return $next($request);
        }

        if (!in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $next($request);
        }

        // Allow internal/framework endpoints to work without forcing a captcha token
        // (e.g. health checks). Add more here if needed.
        if ($request->is('up')) {
            return $next($request);
        }

        $token = (string) ($request->input('g-recaptcha-response') ?: $request->header('X-Recaptcha-Token'));
        $expectedAction = (string) ($request->input('recaptcha_action') ?: '');

        if ($token === '') {
            return $this->fail($request);
        }

        try {
            $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            $data = $response->json() ?: [];

            if (!($data['success'] ?? false)) {
                return $this->fail($request);
            }

            $score = (float) ($data['score'] ?? 0.0);
            if ($score < $minScore) {
                return $this->fail($request);
            }

            // If we send an action from the client, ensure it matches what Google returns.
            if ($expectedAction !== '' && isset($data['action']) && (string) $data['action'] !== $expectedAction) {
                return $this->fail($request);
            }
        } catch (ConnectionException $e) {
            // Log the connection error but allow the request to proceed
            // This prevents blocking users when Google's servers are unreachable
            Log::warning('reCAPTCHA verification failed due to connection error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            
            // In production, you might want to fail here instead:
            // return $this->fail($request);
            
            // For now, allow the request to proceed when Google is unreachable
            return $next($request);
        } catch (\Exception $e) {
            // Log any other unexpected errors
            Log::error('reCAPTCHA verification error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);
            
            // Allow request to proceed on unexpected errors
            return $next($request);
        }

        return $next($request);
    }

    private function fail(Request $request)
    {
        $message = __('translation.messages.recaptcha_failed');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return back()->withErrors([
            'recaptcha' => $message,
        ])->withInput();
    }
}
