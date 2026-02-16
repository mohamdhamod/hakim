<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Patient;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExaminationController extends Controller
{
    use ClinicAuthorization;

    /**
     * Build redirect URL: append section hash if previous URL is the patient show page.
     */
    private function buildRedirectUrl(Patient $patient, string $section = '#examinations-section'): string
    {
        $previous = url()->previous();
        $showUrl = route('clinic.patients.show', ['patient' => $patient->file_number]);

        if ($previous && str_starts_with(strtok($previous, '#?'), strtok($showUrl, '#?'))) {
            return strtok($previous, '#') . $section;
        }

        return $previous ?: $showUrl . $section;
    }

    /**
     * Store a newly created examination.
     */
    public function store(Request $request)
    {
        $clinic = $this->authorizeClinicAccess(true);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'examination_date' => 'required|date',
            'chief_complaint' => 'nullable|string|max:2000',
            'present_illness_history' => 'nullable|string|max:5000',
            'temperature' => 'nullable|numeric|between:30,45',
            'blood_pressure_systolic' => 'nullable|integer|between:60,250',
            'blood_pressure_diastolic' => 'nullable|integer|between:40,150',
            'pulse_rate' => 'nullable|integer|between:30,200',
            'respiratory_rate' => 'nullable|integer|between:8,60',
            'weight' => 'nullable|numeric|between:0.5,500',
            'height' => 'nullable|numeric|between:20,300',
            'oxygen_saturation' => 'nullable|integer|between:50,100',
            'physical_examination' => 'nullable|string|max:5000',
            'diagnosis' => 'nullable|string|max:2000',
            'icd_code' => 'nullable|string|max:20',
            'treatment_plan' => 'nullable|string|max:5000',
            'prescriptions' => 'nullable|string|max:5000',
            'lab_tests_ordered' => 'nullable|string|max:2000',
            'lab_tests_results' => 'nullable|string|max:5000',
            'imaging_ordered' => 'nullable|string|max:2000',
            'imaging_results' => 'nullable|string|max:5000',
            'follow_up_date' => 'nullable|date|after:today',
            'follow_up_notes' => 'nullable|string|max:1000',
            'doctor_notes' => 'nullable|string|max:5000',
        ]);

        // Verify patient belongs to this clinic
        $patient = Patient::findOrFail($validated['patient_id']);
        if (!$patient->belongsToClinic($clinic->id)) {
            abort(403);
        }

        $validated['clinic_id'] = $clinic->id;
        $validated['user_id'] = Auth::id();
        $validated['examination_number'] = Examination::generateExaminationNumber($patient->id);

        $examination = Examination::create($validated);

        $redirectUrl = route('clinic.patients.show', [
            'patient' => $examination->patient->file_number,
            'tab' => 'examinations',
            'examination' => $examination->id
        ]) . '#examinations-section';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.examination.created_successfully'),
                'examination' => $examination,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.examination.created_successfully'));
    }



    /**
     * Update the specified examination.
     */
    public function update(Request $request, $lang , Examination $examination)
    {
        $this->authorizeClinicModel($examination, true);

        $validated = $request->validate([
            'examination_date' => 'required|date',
            'chief_complaint' => 'nullable|string|max:2000',
            'present_illness_history' => 'nullable|string|max:5000',
            'temperature' => 'nullable|numeric|between:30,45',
            'blood_pressure_systolic' => 'nullable|integer|between:60,250',
            'blood_pressure_diastolic' => 'nullable|integer|between:40,150',
            'pulse_rate' => 'nullable|integer|between:30,200',
            'respiratory_rate' => 'nullable|integer|between:8,60',
            'weight' => 'nullable|numeric|between:0.5,500',
            'height' => 'nullable|numeric|between:20,300',
            'oxygen_saturation' => 'nullable|integer|between:50,100',
            'physical_examination' => 'nullable|string|max:5000',
            'diagnosis' => 'nullable|string|max:2000',
            'icd_code' => 'nullable|string|max:20',
            'treatment_plan' => 'nullable|string|max:5000',
            'prescriptions' => 'nullable|string|max:5000',
            'lab_tests_ordered' => 'nullable|string|max:2000',
            'lab_tests_results' => 'nullable|string|max:5000',
            'imaging_ordered' => 'nullable|string|max:2000',
            'imaging_results' => 'nullable|string|max:5000',
            'follow_up_date' => 'nullable|date',
            'follow_up_notes' => 'nullable|string|max:1000',
            'doctor_notes' => 'nullable|string|max:5000',
        ]);

        $examination->update($validated);

         $redirectUrl = $this->buildRedirectUrl($examination->patient);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.examination.updated_successfully'),
                'examination' => $examination,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.examination.updated_successfully'));
    }

    /**
     * Print examination report.
     */
    public function print($lang , Examination $examination)
    {
        $this->authorizeClinicModel($examination);

        $examination->load(['patient', 'clinic', 'doctor']);

        return view('clinic.examinations.print', compact('examination'));
    }

    /**
     * Remove the specified examination from storage.
     */
    public function destroy($lang, Examination $examination)
    {
        $this->authorizeClinicModel($examination, true);

        $patient = $examination->patient;
        $examination->delete();

        $redirectUrl = $this->buildRedirectUrl($patient);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.examination.deleted_successfully'),
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.examination.deleted_successfully'));
    }
}
