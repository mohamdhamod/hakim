<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDoctorOnly
{
    /**
     * Handle an incoming request.
     * 
     * Ensures only doctors can access this route.
     * Used for examination creation/editing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is logged in
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('translation.auth.login_required')], 401);
            }
            return redirect()->route('login');
        }

        // Check if user is a doctor
        if (!$user->isDoctor()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('translation.clinic.doctors_only')], 403);
            }
            return back()->with('error', __('translation.clinic.doctors_only'));
        }

        return $next($request);
    }
}
