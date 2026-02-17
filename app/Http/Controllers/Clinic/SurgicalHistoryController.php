<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\SurgicalHistory;
use App\Models\Patient;
use Illuminate\Http\Request;

class SurgicalHistoryController extends Controller
{
    public function store(Request $request, string $locale, Patient $patient)
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic || !$patient->belongsToClinic($clinic->id)) {
            return response()->json(['success' => false, 'message' => __('translation.common.unauthorized')], 403);
        }

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
        $validated['recorded_by_user_id'] = auth()->id();

        $surgery = SurgicalHistory::create($validated);

        return response()->json([
            'success' => true,
            'message' => __('translation.surgical.created'),
            'data' => $surgery,
        ]);
    }

    public function update(Request $request, string $locale, Patient $patient, SurgicalHistory $surgery)
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic || $surgery->clinic_id !== $clinic->id) {
            return response()->json(['success' => false, 'message' => __('translation.common.unauthorized')], 403);
        }

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

        return response()->json([
            'success' => true,
            'message' => __('translation.surgical.updated'),
            'data' => $surgery,
        ]);
    }

    public function destroy(string $locale, Patient $patient, SurgicalHistory $surgery)
    {
        $clinic = auth()->user()->clinic;

        if (!$clinic || $surgery->clinic_id !== $clinic->id) {
            return response()->json(['success' => false, 'message' => __('translation.common.unauthorized')], 403);
        }

        $surgery->delete();

        return response()->json([
            'success' => true,
            'message' => __('translation.surgical.deleted'),
        ]);
    }
}
