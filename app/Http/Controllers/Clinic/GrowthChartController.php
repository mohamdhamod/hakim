<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\GrowthMeasurement;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrowthChartController extends Controller
{
    /**
     * Display a listing of growth measurements for a patient.
     */
    public function index(Patient $patient)
    {
        $this->authorize('view', $patient);

        $measurements = GrowthMeasurement::with(['measuredBy', 'examination'])
            ->forPatient($patient->id)
            ->chronological()
            ->get();

        // Prepare chart data
        $chartData = $measurements->map(function ($measurement) {
            return [
                'age_months' => $measurement->age_months,
                'weight' => $measurement->weight_kg,
                'height' => $measurement->height_cm,
                'bmi' => $measurement->bmi,
                'date' => $measurement->measurement_date->format('Y-m-d'),
            ];
        });

        return view('clinic.growth-charts.index', compact('patient', 'measurements', 'chartData'));
    }

    /**
     * Show the form for creating a new growth measurement.
     */
    public function create(Patient $patient)
    {
        $this->authorize('update', $patient);

        // Calculate current age in months
        $ageMonths = $patient->date_of_birth ? $patient->date_of_birth->diffInMonths(now()) : 0;

        return view('clinic.growth-charts.create', compact('patient', 'ageMonths'));
    }

    /**
     * Store a newly created growth measurement in storage.
     */
    public function store(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'measurement_date' => 'required|date',
            'age_months' => 'required|integer|min:0',
            'weight_kg' => 'nullable|numeric|min:0|max:500',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'head_circumference_cm' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['measured_by_user_id'] = Auth::id();

        // Create measurement
        $measurement = new GrowthMeasurement($validated);
        
        // Calculate BMI
        $measurement->calculateBmi();
        
        // TODO: Calculate percentiles based on WHO standards
        // This would require implementing WHO growth chart algorithms
        
        $measurement->save();

        return redirect()
            ->route('patients.growth-charts.index', $patient)
            ->with('success', __('translation.growth_measurement_added_successfully'));
    }

    /**
     * Display the specified growth measurement.
     */
    public function show(Patient $patient, GrowthMeasurement $growthChart)
    {
        $this->authorize('view', $patient);

        $growthChart->load(['measuredBy', 'examination']);

        return view('clinic.growth-charts.show', compact('patient', 'growthChart'));
    }

    /**
     * Show the form for editing the specified growth measurement.
     */
    public function edit(Patient $patient, GrowthMeasurement $growthChart)
    {
        $this->authorize('update', $patient);

        return view('clinic.growth-charts.edit', compact('patient', 'growthChart'));
    }

    /**
     * Update the specified growth measurement in storage.
     */
    public function update(Request $request, Patient $patient, GrowthMeasurement $growthChart)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'measurement_date' => 'required|date',
            'age_months' => 'required|integer|min:0',
            'weight_kg' => 'nullable|numeric|min:0|max:500',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'head_circumference_cm' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $growthChart->fill($validated);
        $growthChart->calculateBmi();
        $growthChart->save();

        return redirect()
            ->route('patients.growth-charts.show', [$patient, $growthChart])
            ->with('success', __('translation.growth_measurement_updated_successfully'));
    }

    /**
     * Remove the specified growth measurement from storage.
     */
    public function destroy(Patient $patient, GrowthMeasurement $growthChart)
    {
        $this->authorize('update', $patient);

        $growthChart->delete();

        return redirect()
            ->route('patients.growth-charts.index', $patient)
            ->with('success', __('translation.growth_measurement_deleted_successfully'));
    }

    /**
     * Display growth chart visualization.
     */
    public function chart(Patient $patient)
    {
        $this->authorize('view', $patient);

        $measurements = GrowthMeasurement::forPatient($patient->id)
            ->chronological()
            ->get();

        // Prepare data for Chart.js
        $weightData = $measurements->pluck('weight_kg', 'age_months')->toArray();
        $heightData = $measurements->pluck('height_cm', 'age_months')->toArray();
        $bmiData = $measurements->pluck('bmi', 'age_months')->toArray();

        return view('clinic.growth-charts.chart', compact('patient', 'measurements', 'weightData', 'heightData', 'bmiData'));
    }
}
