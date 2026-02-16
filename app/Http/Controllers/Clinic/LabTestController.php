<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\LabTestResult;
use App\Models\Patient;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabTestController extends Controller
{
    use ClinicAuthorization;

    /**
     * Build redirect URL: append section hash if previous URL is the patient show page.
     */
    private function buildRedirectUrl(Patient $patient, string $section = '#lab-tests-section'): string
    {
        $previous = url()->previous();
        $showUrl = route('clinic.patients.show', $patient);

        if ($previous && str_starts_with(strtok($previous, '#?'), strtok($showUrl, '#?'))) {
            return strtok($previous, '#') . $section;
        }

        return $previous ?: $showUrl . $section;
    }

    /**
     * Store a newly created lab test in storage.
     */
    public function store(Request $request, $lang, Patient $patient)
    {
        $clinic = $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'lab_test_type_id' => 'required|exists:lab_test_types,id',
            'test_date' => 'required|date',
            'result_value' => 'required|numeric',
            'result_text' => 'nullable|string',
            'status' => 'nullable|in:pending,completed,cancelled',
            'lab_name' => 'nullable|string|max:255',
            'lab_reference_number' => 'nullable|string|max:255',
            'interpretation' => 'nullable|in:normal,abnormal_low,abnormal_high,critical',
            'notes' => 'nullable|string',
        ]);

        // Map 'notes' to 'doctor_notes' if provided
        if (isset($validated['notes'])) {
            $validated['doctor_notes'] = $validated['notes'];
            unset($validated['notes']);
        }

        $validated['patient_id'] = $patient->id;
        $validated['clinic_id'] = $clinic->id;
        $validated['ordered_by_user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'completed';

        $labTest = LabTestResult::create($validated);

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.lab_test_added_successfully'),
                'data' => $labTest,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.lab_test_added_successfully'));
    }

    /**
     * Update the specified lab test in storage.
     */
    public function update(Request $request, $lang, Patient $patient, LabTestResult $labTest)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($labTest, true);

        $validated = $request->validate([
            'lab_test_type_id' => 'required|exists:lab_test_types,id',
            'test_date' => 'required|date',
            'result_value' => 'nullable|string',
            'result_text' => 'nullable|string',
            'status' => 'nullable|in:pending,completed,cancelled',
            'lab_name' => 'nullable|string|max:255',
            'lab_reference_number' => 'nullable|string|max:255',
            'interpretation' => 'nullable|in:normal,abnormal_low,abnormal_high,critical',
            'doctor_notes' => 'nullable|string',
        ]);

        $labTest->update($validated);

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.lab_test_updated_successfully'),
                'data' => $labTest,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.lab_test_updated_successfully'));
    }

    /**
     * Remove the specified lab test from storage.
     */
    public function destroy($lang, Patient $patient, LabTestResult $labTest)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($labTest, true);

        $labTest->delete();

        $redirectUrl = $this->buildRedirectUrl($patient);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.lab_test_deleted_successfully'),
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.lab_test_deleted_successfully'));
    }
}
