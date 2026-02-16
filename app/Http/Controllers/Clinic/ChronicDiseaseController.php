<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ChronicDiseaseMonitoring;
use App\Models\Patient;
use App\Models\PatientChronicDisease;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChronicDiseaseController extends Controller
{
    use ClinicAuthorization;

    /**
     * Build redirect URL: append section hash if previous URL is the patient show page.
     */
    private function buildRedirectUrl(Patient $patient, string $section = '#chronic-diseases-section'): string
    {
        $previous = url()->previous();
        $showUrl = route('clinic.patients.show', $patient);

        if ($previous && str_starts_with(strtok($previous, '#?'), strtok($showUrl, '#?'))) {
            return strtok($previous, '#') . $section;
        }

        return $previous ?: $showUrl . $section;
    }

    /**
     * Display the specified chronic disease.
     */
    public function show($lang, Patient $patient, PatientChronicDisease $patientChronicDisease)
    {
        $this->authorizePatientAccess($patient);

        // Verify the disease belongs to this patient
        if ($patientChronicDisease->patient_id !== $patient->id) {
            abort(404);
        }

        $disease = $patientChronicDisease->load(['chronicDiseaseType.translations', 'monitoringRecords']);

        return response()->json([
            'success' => true,
            'disease' => [
                'id' => $disease->id,
                'type_name' => $disease->chronicDiseaseType->name,
                'category' => $disease->chronicDiseaseType->category,
                'icd11_code' => $disease->chronicDiseaseType->icd11_code,
                'status' => $disease->status,
                'severity' => $disease->severity,
                'diagnosis_date' => $disease->diagnosis_date,
                'last_followup_date' => $disease->last_followup_date,
                'next_followup_date' => $disease->next_followup_date,
                'treatment_plan' => $disease->treatment_plan,
                'monitoring_count' => $disease->monitoringRecords->count(),
            ]
        ]);
    }

    /**
     * Store a newly created chronic disease in storage.
     */
    public function store(Request $request, $lang, Patient $patient)
    {
        $clinic = $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'chronic_disease_type_id' => 'required|exists:chronic_disease_types,id',
            'diagnosis_date' => 'required|date',
            'severity' => 'nullable|in:mild,moderate,severe',
            'status' => 'nullable|in:active,in_remission,resolved',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
        ]);

        // Check if patient already has this chronic disease type
        $existingDisease = PatientChronicDisease::where('patient_id', $patient->id)
            ->where('chronic_disease_type_id', $validated['chronic_disease_type_id'])
            ->first();

        if ($existingDisease) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('translation.chronic_disease_already_exists')
                ], 422);
            }
            return redirect()->back()->with('error', __('translation.chronic_disease_already_exists'));
        }

        $validated['patient_id'] = $patient->id;
        $validated['clinic_id'] = $clinic->id;
        $validated['diagnosed_by_user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'active';

        $disease = PatientChronicDisease::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.chronic_disease_added_successfully'),
                'redirect' => $this->buildRedirectUrl($patient),
                'data' => $disease
            ]);
        }

        return redirect()
            ->route('clinic.patients.show', $patient)
            ->with('success', __('translation.chronic_disease_added_successfully'));
    }

    /**
     * Update the specified chronic disease in storage.
     */
    public function update(Request $request, $lang, Patient $patient, PatientChronicDisease $patientChronicDisease)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($patientChronicDisease, true);

        $validated = $request->validate([
            'chronic_disease_type_id' => 'required|exists:chronic_disease_types,id',
            'diagnosis_date' => 'required|date',
            'severity' => 'nullable|in:mild,moderate,severe',
            'status' => 'nullable|in:active,in_remission,resolved',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'last_followup_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
        ]);

        $patientChronicDisease->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.chronic_disease_updated_successfully'),
                'redirect' => $this->buildRedirectUrl($patient),
                'data' => $patientChronicDisease
            ]);
        }

        return redirect()
            ->route('clinic.patients.show', $patient)
            ->with('success', __('translation.chronic_disease_updated_successfully'));
    }

    /**
     * Remove the specified chronic disease from storage.
     */
    public function destroy($lang, Patient $patient, PatientChronicDisease $patientChronicDisease)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($patientChronicDisease, true);

        $patientChronicDisease->delete();

        $redirectUrl = $this->buildRedirectUrl($patient);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.chronic_disease_deleted_successfully'),
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.chronic_disease_deleted_successfully'));
    }

    /**
     * Store a new monitoring record.
     */
    public function storeMonitoring(Request $request, $lang, Patient $patient, PatientChronicDisease $patientChronicDisease)
    {
        $clinic = $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($patientChronicDisease, true);

        $validated = $request->validate([
            'monitoring_date' => 'required|date',
            'parameter_name' => 'required|string|max:255',
            'parameter_value' => 'required|string|max:255',
            'parameter_unit' => 'nullable|string|max:50',
            'status' => 'nullable|in:controlled,uncontrolled,critical',
            'notes' => 'nullable|string',
        ]);

        $validated['patient_chronic_disease_id'] = $patientChronicDisease->id;
        $validated['clinic_id'] = $clinic->id;
        $validated['recorded_by_user_id'] = Auth::id();

        ChronicDiseaseMonitoring::create($validated);

        // Update last follow-up date
        $patientChronicDisease->update([
            'last_followup_date' => $validated['monitoring_date'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.monitoring_record_added_successfully'),
                'redirect' => $this->buildRedirectUrl($patient),
            ]);
        }

        return redirect()
            ->route('clinic.patients.show', $patient)
            ->with('success', __('translation.monitoring_record_added_successfully'));
    }
}
