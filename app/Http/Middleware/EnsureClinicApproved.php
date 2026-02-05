<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicApproved
{
    /**
     * Handle an incoming request.
     * 
     * Ensures the doctor has an approved clinic before allowing access to clinic routes.
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
            return redirect()->route('home')
                ->with('error', __('translation.clinic.doctors_only'));
        }

        // Check if user has a clinic
        $clinic = $user->clinic;
        
        if (!$clinic) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('translation.clinic.no_clinic')], 403);
            }
            return response()->view('clinic.no-clinic');
        }

        // Check clinic status
        if ($clinic->isPending()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('translation.clinic.pending_approval')], 403);
            }
            return response()->view('clinic.pending-approval', compact('clinic'));
        }

        if ($clinic->isRejected()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => __('translation.clinic.rejected')], 403);
            }
            return response()->view('clinic.rejected', compact('clinic'));
        }

        return $next($request);
    }
}
