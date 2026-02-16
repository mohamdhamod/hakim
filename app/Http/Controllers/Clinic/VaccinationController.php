<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\VaccinationRecord;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccinationController extends Controller
{
    use ClinicAuthorization;

    /**
     * Build redirect URL: append section hash if previous URL is the patient show page.
     */
    private function buildRedirectUrl(Patient $patient, string $section = '#vaccinations-section'): string
    {
        $previous = url()->previous();
        $showUrl = route('clinic.patients.show', $patient);

        // If previous URL is the patient show page (with or without hash/query), append section hash
        if ($previous && str_starts_with(strtok($previous, '#?'), strtok($showUrl, '#?'))) {
            return strtok($previous, '#') . $section;
        }

        // Otherwise return previous URL as-is (e.g., all-vaccinations page)
        return $previous ?: $showUrl . $section;
    }

    /**
     * Store a newly created vaccination record in storage.
     */
    public function store(Request $request, $lang, Patient $patient)
    {
        $clinic = $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'vaccination_type_id' => 'required|exists:vaccination_types,id',
            'vaccination_date' => 'required|date',
            'dose_number' => 'nullable|integer|min:1',
            'batch_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'site' => 'nullable|string|max:255',
            'reaction_notes' => 'nullable|string',
            'next_dose_due_date' => 'nullable|date',
            'status' => 'nullable|in:scheduled,completed,missed,cancelled',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['clinic_id'] = $clinic->id;
        $validated['administered_by_user_id'] = Auth::id();
        $validated['dose_number'] = $validated['dose_number'] ?? 1;
        $validated['status'] = $validated['status'] ?? 'completed';

        $vaccination = VaccinationRecord::create($validated);

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.vaccination_added_successfully'),
                'data' => $vaccination,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.vaccination_added_successfully'));
    }

    /**
     * Update the specified vaccination record in storage.
     */
    public function update(Request $request, $lang, Patient $patient, VaccinationRecord $vaccination)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($vaccination, true);

        $validated = $request->validate([
            'vaccination_type_id' => 'required|exists:vaccination_types,id',
            'vaccination_date' => 'required|date',
            'dose_number' => 'nullable|integer|min:1',
            'batch_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'site' => 'nullable|string|max:255',
            'reaction_notes' => 'nullable|string',
            'next_dose_due_date' => 'nullable|date',
            'status' => 'nullable|in:scheduled,completed,missed,cancelled',
        ]);

        $vaccination->update($validated);

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.vaccination_updated_successfully'),
                'data' => $vaccination,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.vaccination_updated_successfully'));
    }

    /**
     * Remove the specified vaccination record from storage.
     */
    public function destroy($lang, Patient $patient, VaccinationRecord $vaccination)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($vaccination, true);

        $vaccination->delete();

        $redirectUrl = $this->buildRedirectUrl($patient);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.vaccination_deleted_successfully'),
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.vaccination_deleted_successfully'));
    }
}
