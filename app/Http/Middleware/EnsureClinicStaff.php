<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicStaff
{
    /**
     * Handle an incoming request.
     * 
     * Ensures the user is either a doctor with an approved clinic 
     * or an active clinic patient editor.
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

        // Get the working clinic
        $clinic = $user->workingClinic;

        // If user is a doctor
        if ($user->isDoctor()) {
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

            // Share clinic with views
            view()->share('clinic', $clinic);
            view()->share('userRole', 'doctor');
            
            return $next($request);
        }

        // If user is a clinic patient editor
        if ($user->isClinicPatientEditor()) {
            $clinic = $user->editorClinic;
            
            if (!$clinic) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => __('translation.clinic.no_clinic')], 403);
                }
                return redirect()->route('home')
                    ->with('error', __('translation.clinic.no_clinic'));
            }

            // Check if the editor is active
            $clinicUser = \App\Models\ClinicUser::where('clinic_id', $clinic->id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();

            if (!$clinicUser) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => __('translation.clinic.account_deactivated')], 403);
                }
                return redirect()->route('home')
                    ->with('error', __('translation.clinic.account_deactivated'));
            }

            // Share clinic with views
            view()->share('clinic', $clinic);
            view()->share('userRole', 'editor');
            
            return $next($request);
        }

        // User is neither doctor nor editor
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => __('translation.clinic.unauthorized')], 403);
        }
        return redirect()->route('home')
            ->with('error', __('translation.clinic.unauthorized'));
    }
}
