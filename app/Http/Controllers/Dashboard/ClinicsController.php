<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;

class ClinicsController extends Controller
{
    /**
     * Display listing of clinics.
     */
    public function index(Request $request)
    {
        $clinics = Clinic::with(['doctor', 'specialty'])
            ->withCount('patients')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('dashboard.clinics.index', compact('clinics'));
    }

    /**
     * Display pending clinics for approval.
     */
    public function pending(Request $request)
    {
        $clinics = Clinic::with(['doctor', 'specialty'])
            ->pending()
            ->orderBy('created_at', 'asc')
            ->paginate(12);

        return view('dashboard.clinics.pending', compact('clinics'));
    }

    /**
     * Show clinic details.
     */
    public function show($lang, Clinic $clinic)
    {
        $clinic->load(['doctor', 'patients', 'approver']);
        return view('dashboard.clinics.show', compact('clinic'));
    }

    /**
     * Approve a clinic.
     */
    public function approve(Request $request,$lang, Clinic $clinic)
    {
        if ($clinic->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('translation.clinic.already_processed'),
            ], 422);
        }

        $clinic->approve(auth()->id());
        
        // Also approve the doctor
        $clinic->doctor->approveDoctor();

        return response()->json([
            'success' => true,
            'message' => __('translation.clinic.approved_successfully'),
        ]);
    }

    /**
     * Reject a clinic.
     */
    public function reject(Request $request,$lang, Clinic $clinic)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if ($clinic->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => __('translation.clinic.already_processed'),
            ], 422);
        }

        $clinic->reject($request->reason);
        
        // Also reject the doctor
        $clinic->doctor->rejectDoctor($request->reason);

        return response()->json([
            'success' => true,
            'message' => __('translation.clinic.rejected_successfully'),
        ]);
    }
}
