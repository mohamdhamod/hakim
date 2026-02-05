<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !$request->hasSession()) {
            return $next($request);
        }

        $user = auth()->user();
        $currentSessionId = $request->session()->getId();
        $lockedSessionId = $user->current_session_id ?? null;

        // If the session ID was regenerated right after login (e.g. Fortify's
        // PrepareAuthenticatedSession), allow a one-time sync to avoid
        // immediately logging out the freshly authenticated user.
        if (
            $lockedSessionId
            && $currentSessionId
            && $lockedSessionId !== $currentSessionId
            && $request->session()->pull('single_session_lock_pending', false)
        ) {
            $user->forceFill([
                'current_session_id' => $currentSessionId,
            ])->save();

            return $next($request);
        }

        // Clean up the flag if it exists and the session is already in sync.
        if ($request->session()->has('single_session_lock_pending') && $lockedSessionId === $currentSessionId) {
            $request->session()->forget('single_session_lock_pending');
        }

        if ($lockedSessionId && $currentSessionId && $lockedSessionId !== $currentSessionId) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('auth_error', __('translation.messages.single_session_kicked'));
        }

        return $next($request);
    }
}
