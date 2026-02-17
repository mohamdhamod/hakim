<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientProblem;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProblemListController extends Controller
{
    use ClinicAuthorization;

    /**
     * Build redirect URL: append section hash if previous URL is the patient show page.
     */
    private function buildRedirectUrl(Patient $patient, string $section = '#problem-list-section'): string
    {
        $previous = url()->previous();
        $showUrl = route('clinic.patients.show', $patient);

        if ($previous && str_starts_with(strtok($previous, '#?'), strtok($showUrl, '#?'))) {
            return strtok($previous, '#') . $section;
        }

        return $previous ?: $showUrl . $section;
    }

    /**
     * Store a newly created problem record.
     */
    public function store(Request $request, $lang, Patient $patient)
    {
        $clinic = $this->authorizePatientAccess($patient, true);

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
        $validated['recorded_by_user_id'] = Auth::id();

        $problem = PatientProblem::create($validated);

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.problem_list.created'),
                'data' => $problem,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.problem_list.created'));
    }

    /**
     * Update the specified problem record.
     */
    public function update(Request $request, $lang, Patient $patient, PatientProblem $problem)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($problem, true);

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

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.problem_list.updated'),
                'data' => $problem,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.problem_list.updated'));
    }

    /**
     * Remove the specified problem record.
     */
    public function destroy($lang, Patient $patient, PatientProblem $problem)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($problem, true);

        $problem->delete();

        $redirectUrl = $this->buildRedirectUrl($patient);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.problem_list.deleted'),
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.problem_list.deleted'));
    }
}
