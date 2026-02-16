<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display clinic dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        // Check if user is a doctor
        if (!$user->isDoctor()) {
            return redirect()->route('home')
                ->with('error', __('translation.clinic.doctors_only'));
        }

        // Check if clinic exists
        if (!$clinic) {
            return view('clinic.no-clinic');
        }

        // Check approval status
        if ($clinic->isPending()) {
            return view('clinic.pending-approval', compact('clinic'));
        }

        if ($clinic->status === 'rejected') {
            return view('clinic.rejected', compact('clinic'));
        }

        // Get dashboard statistics
        $stats = [
            'total_patients' => Patient::forClinic($clinic->id)->count(),
            'today_examinations' => Examination::where('clinic_id', $clinic->id)
                ->today()
                ->count(),
            'pending_examinations' => Examination::where('clinic_id', $clinic->id)
                ->where('status', 'scheduled')
                ->count(),
            'completed_examinations' => Examination::where('clinic_id', $clinic->id)
                ->where('status', 'completed')
                ->count(),
            'this_month_examinations' => Examination::where('clinic_id', $clinic->id)
                ->whereMonth('examination_date', now()->month)
                ->whereYear('examination_date', now()->year)
                ->count(),
        ];

        // Get recent patients
        $recentPatients = Patient::forClinic($clinic->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get today's appointments
        $todayExaminations = Examination::with('patient')
            ->where('clinic_id', $clinic->id)
            ->today()
            ->orderBy('examination_date')
            ->get();

        // Get upcoming follow-ups
        $upcomingFollowUps = Examination::with('patient')
            ->where('clinic_id', $clinic->id)
            ->whereNotNull('follow_up_date')
            ->where('follow_up_date', '>=', now())
            ->where('follow_up_date', '<=', now()->addDays(7))
            ->orderBy('follow_up_date')
            ->limit(5)
            ->get();

        return view('clinic.dashboard', compact(
            'clinic',
            'stats',
            'recentPatients',
            'todayExaminations',
            'upcomingFollowUps'
        ));
    }

    /**
     * Show clinic settings.
     */
    public function settings()
    {
        $clinic = Auth::user()->clinic;

        if (!$clinic || !$clinic->isApproved()) {
            return redirect()->route('clinic.dashboard');
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
