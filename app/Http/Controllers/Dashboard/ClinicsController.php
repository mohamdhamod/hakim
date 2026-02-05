<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClinicsController extends Controller
{
    /**
     * Display listing of clinics.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clinics = Clinic::with(['doctor'])
                ->select('clinics.*');

            return DataTables::of($clinics)
                ->addColumn('doctor_name', function ($clinic) {
                    return $clinic->doctor->name ?? '-';
                })
                ->addColumn('doctor_email', function ($clinic) {
                    return $clinic->doctor->email ?? '-';
                })
                ->addColumn('status_badge', function ($clinic) {
                    return '<span class="badge ' . $clinic->status_badge_class . '">' . $clinic->status_label . '</span>';
                })
                ->addColumn('patients_count', function ($clinic) {
                    return $clinic->patients()->count();
                })
                ->addColumn('actions', function ($clinic) {
                    $actions = '<div class="btn-group">';
                    $actions .= '<a href="' . route('clinics.show', $clinic->id) . '" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>';
                    
                    if ($clinic->status === 'pending') {
                        $actions .= '<button type="button" class="btn btn-sm btn-success approve-btn" data-id="' . $clinic->id . '"><i class="bi bi-check-lg"></i></button>';
                        $actions .= '<button type="button" class="btn btn-sm btn-danger reject-btn" data-id="' . $clinic->id . '"><i class="bi bi-x-lg"></i></button>';
                    }
                    
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        return view('dashboard.clinics.index');
    }

    /**
     * Display pending clinics for approval.
     */
    public function pending(Request $request)
    {
        if ($request->ajax()) {
            $clinics = Clinic::with(['doctor'])
                ->pending()
                ->select('clinics.*');

            return DataTables::of($clinics)
                ->addColumn('doctor_name', function ($clinic) {
                    return $clinic->doctor->name ?? '-';
                })
                ->addColumn('doctor_email', function ($clinic) {
                    return $clinic->doctor->email ?? '-';
                })
                ->addColumn('doctor_phone', function ($clinic) {
                    return $clinic->doctor->phone ?? '-';
                })
                ->addColumn('created_at_formatted', function ($clinic) {
                    return $clinic->created_at->format('Y-m-d H:i');
                })
                ->addColumn('actions', function ($clinic) {
                    return '
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-success approve-btn" data-id="' . $clinic->id . '">
                                <i class="bi bi-check-lg"></i> ' . __('translation.common.approve') . '
                            </button>
                            <button type="button" class="btn btn-sm btn-danger reject-btn" data-id="' . $clinic->id . '">
                                <i class="bi bi-x-lg"></i> ' . __('translation.common.reject') . '
                            </button>
                        </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('dashboard.clinics.pending');
    }

    /**
     * Show clinic details.
     */
    public function show(Clinic $clinic)
    {
        $clinic->load(['doctor', 'patients', 'approver']);
        return view('dashboard.clinics.show', compact('clinic'));
    }

    /**
     * Approve a clinic.
     */
    public function approve(Request $request, Clinic $clinic)
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
    public function reject(Request $request, Clinic $clinic)
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
