<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\GrowthMeasurement;
use App\Models\Patient;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrowthChartController extends Controller
{
    use ClinicAuthorization;

    /**
     * Store a newly created growth measurement in storage.
     */
    public function store(Request $request, $lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $validated = $request->validate([
            'measurement_date' => 'required|date',
            'age_months' => 'nullable|integer|min:0',
            'weight_kg' => 'nullable|numeric|min:0|max:500',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'head_circumference_cm' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // Calculate age in months if not provided
        if (empty($validated['age_months']) && $patient->date_of_birth) {
            $measurementDate = \Carbon\Carbon::parse($validated['measurement_date']);
            $validated['age_months'] = $patient->date_of_birth->diffInMonths($measurementDate);
        }

        $validated['patient_id'] = $patient->id;
        $validated['measured_by_user_id'] = Auth::id();

        // Create measurement
        $measurement = new GrowthMeasurement($validated);
        
        // Calculate BMI
        $measurement->calculateBmi();
        
        // Calculate WHO percentiles if method exists
        if (method_exists($measurement, 'calculatePercentiles')) {
            $measurement->calculatePercentiles();
        }
        
        $measurement->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.growth_measurement_added_successfully'),
                'data' => $measurement
            ]);
        }

        return redirect()
            ->route('clinic.patients.show', $patient)
            ->with('success', __('translation.growth_measurement_added_successfully'));
    }

    /**
     * Update the specified growth measurement in storage.
     */
    public function update(Request $request, $lang, Patient $patient, GrowthMeasurement $growthChart)
    {
        $this->authorizePatientAccess($patient);

        $validated = $request->validate([
            'measurement_date' => 'required|date',
            'age_months' => 'nullable|integer|min:0',
            'weight_kg' => 'nullable|numeric|min:0|max:500',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'head_circumference_cm' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // Calculate age in months if not provided
        if (empty($validated['age_months']) && $patient->date_of_birth) {
            $measurementDate = \Carbon\Carbon::parse($validated['measurement_date']);
            $validated['age_months'] = $patient->date_of_birth->diffInMonths($measurementDate);
        }

        $growthChart->fill($validated);
        $growthChart->calculateBmi();
        $growthChart->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.growth_measurement_updated_successfully'),
                'data' => $growthChart
            ]);
        }

        return redirect()
            ->route('clinic.patients.show', $patient)
            ->with('success', __('translation.growth_measurement_updated_successfully'));
    }

    /**
     * Remove the specified growth measurement from storage.
     */
    public function destroy($lang, Patient $patient, GrowthMeasurement $growthChart)
    {
        $this->authorizePatientAccess($patient);

        $growthChart->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.growth_measurement_deleted_successfully'),
            ]);
        }

        return redirect()
            ->route('clinic.patients.show', $patient)
            ->with('success', __('translation.growth_measurement_deleted_successfully'));
    }
}
