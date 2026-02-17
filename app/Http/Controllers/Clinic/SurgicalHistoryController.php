<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\SurgicalHistory;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurgicalHistoryController extends Controller
{
    use ClinicAuthorization;

    /**
     * Build redirect URL: append section hash if previous URL is the patient show page.
     */
    private function buildRedirectUrl(Patient $patient, string $section = '#surgical-history-section'): string
    {
        $previous = url()->previous();
        $showUrl = route('clinic.patients.show', $patient);

        if ($previous && str_starts_with(strtok($previous, '#?'), strtok($showUrl, '#?'))) {
            return strtok($previous, '#') . $section;
        }

        return $previous ?: $showUrl . $section;
    }

    /**
     * Store a newly created surgical history record.
     */
    public function store(Request $request, $lang, Patient $patient)
    {
        $clinic = $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'procedure_name' => 'required|string|max:255',
            'procedure_date' => 'nullable|date|before_or_equal:today',
            'hospital' => 'nullable|string|max:255',
            'surgeon' => 'nullable|string|max:255',
            'indication' => 'nullable|string|max:1000',
            'complications' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['clinic_id'] = $clinic->id;
        $validated['recorded_by_user_id'] = Auth::id();

        $surgery = SurgicalHistory::create($validated);

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.surgical_history.created'),
                'data' => $surgery,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.surgical_history.created'));
    }

    /**
     * Update the specified surgical history record.
     */
    public function update(Request $request, $lang, Patient $patient, SurgicalHistory $surgery)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($surgery, true);

        $validated = $request->validate([
            'procedure_name' => 'required|string|max:255',
            'procedure_date' => 'nullable|date|before_or_equal:today',
            'hospital' => 'nullable|string|max:255',
            'surgeon' => 'nullable|string|max:255',
            'indication' => 'nullable|string|max:1000',
            'complications' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        $surgery->update($validated);

        $redirectUrl = $this->buildRedirectUrl($patient);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.surgical_history.updated'),
                'data' => $surgery,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.surgical_history.updated'));
    }

    /**
     * Remove the specified surgical history record.
     */
    public function destroy($lang, Patient $patient, SurgicalHistory $surgery)
    {
        $this->authorizePatientAccess($patient, true);
        $this->authorizeClinicModel($surgery, true);

        $surgery->delete();

        $redirectUrl = $this->buildRedirectUrl($patient);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.surgical_history.deleted'),
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)
            ->with('success', __('translation.surgical_history.deleted'));
    }
}
