<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Update the clinic information for doctors.
     */
    public function updateClinic(Request $request)
    {
        $user = Auth::user();
        
        // Only doctors can update clinic info
        if ($user->user_type !== 'doctor') {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.error_occurred'),
            ], 403);
        }

        $clinic = $user->clinic;
        
        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.not_found'),
            ], 404);
        }

        $validated = $request->validate([
            'clinic_name' => ['required', 'string', 'max:255'],
            'specialty_id' => ['required', 'integer', 'exists:specialties,id'],
            'clinic_address' => ['nullable', 'string', 'max:500'],
        ]);

        $clinic->update([
            'name' => $validated['clinic_name'],
            'specialty_id' => $validated['specialty_id'],
            'address' => $validated['clinic_address'],
        ]);

        return response()->json([
            'success' => true,
            'message' => __('translation.common.updated'),
        ]);
    }
}
