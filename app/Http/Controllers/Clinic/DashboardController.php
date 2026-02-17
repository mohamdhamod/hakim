<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show clinic settings.
     */
    public function settings()
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic || !$clinic->isApproved()) {
            return redirect()->route('clinic.workspace');
        }

        return view('clinic.settings', compact('clinic'));
    }

    /**
     * Update clinic settings.
     */
    public function updateSettings(Request $request)
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic || !$clinic->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => __('translation.clinic.not_approved'),
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
        ]);

        $clinic->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.clinic.settings_updated'),
            ]);
        }

        return redirect()->route('clinic.settings')
            ->with('success', __('translation.clinic.settings_updated'));
    }
}
