<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\PatientProblem;
use App\Models\Patient;
use Illuminate\Http\Request;

class ProblemListController extends Controller
{
    public function store(Request $request, string $locale, Patient $patient)
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic || !$patient->belongsToClinic($clinic->id)) {
            return response()->json(['success' => false, 'message' => __('translation.common.unauthorized')], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icd_code' => 'nullable|string|max:20',
            'onset_date' => 'nullable|date|before_or_equal:today',
            'status' => 'required|in:active,resolved,inactive',
            'severity' => 'nullable|in:mild,moderate,severe',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['clinic_id'] = $clinic->id;
        $validated['recorded_by_user_id'] = auth()->id();

        $problem = PatientProblem::create($validated);

        return response()->json([
            'success' => true,
            'message' => __('translation.problem.created'),
            'data' => $problem,
        ]);
    }

    public function update(Request $request, string $locale, Patient $patient, PatientProblem $problem)
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic || $problem->clinic_id !== $clinic->id) {
            return response()->json(['success' => false, 'message' => __('translation.common.unauthorized')], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icd_code' => 'nullable|string|max:20',
            'onset_date' => 'nullable|date|before_or_equal:today',
            'resolved_date' => 'nullable|date|before_or_equal:today',
            'status' => 'required|in:active,resolved,inactive',
            'severity' => 'nullable|in:mild,moderate,severe',
            'notes' => 'nullable|string|max:1000',
        ]);

        $problem->update($validated);

        return response()->json([
            'success' => true,
            'message' => __('translation.problem.updated'),
            'data' => $problem,
        ]);
    }

    public function destroy(string $locale, Patient $patient, PatientProblem $problem)
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic || $problem->clinic_id !== $clinic->id) {
            return response()->json(['success' => false, 'message' => __('translation.common.unauthorized')], 403);
        }

        $problem->delete();

        return response()->json([
            'success' => true,
            'message' => __('translation.problem.deleted'),
        ]);
    }
}
