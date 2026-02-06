<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\VaccinationRecord;
use App\Models\VaccinationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccinationController extends Controller
{
    /**
     * Display a listing of vaccinations for a patient.
     */
    public function index($lang ,Patient $patient)
    {
        $this->authorize('view', $patient);

        $vaccinations = VaccinationRecord::with(['vaccinationType', 'administeredBy'])
            ->where('patient_id', $patient->id)
            ->latest('vaccination_date')
            ->paginate(20);

        // Get missing mandatory vaccinations
        $completedVaccinations = $vaccinations->pluck('vaccination_type_id')->toArray();
        $mandatoryTypes = VaccinationType::mandatory()->active()->get();
        $missingVaccinations = $mandatoryTypes->filter(function ($type) use ($completedVaccinations) {
            return !in_array($type->id, $completedVaccinations);
        });

        return view('clinic.vaccinations.index', compact('patient', 'vaccinations', 'missingVaccinations'));
    }

    /**
     * Show the form for creating a new vaccination record.
     */
    public function create($lang ,Patient $patient)
    {
        $this->authorize('update', $patient);

        $vaccinationTypes = VaccinationType::active()->ordered()->get();

        return view('clinic.vaccinations.create', compact('patient', 'vaccinationTypes'));
    }

    /**
     * Store a newly created vaccination record in storage.
     */
    public function store(Request $request,$lang , Patient $patient)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'vaccination_type_id' => 'required|exists:vaccination_types,id',
            'vaccination_date' => 'required|date',
            'dose_number' => 'required|integer|min:1',
            'batch_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'site' => 'nullable|string|max:255',
            'reaction_notes' => 'nullable|string',
            'next_dose_due_date' => 'nullable|date',
            'status' => 'required|in:scheduled,completed,missed,cancelled',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['administered_by_user_id'] = Auth::id();

        $vaccination = VaccinationRecord::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.vaccination_added_successfully'),
                'data' => $vaccination
            ]);
        }

        return redirect()
            ->route('patients.vaccinations.index', $patient)
            ->with('success', __('translation.vaccination_added_successfully'));
    }

    /**
     * Display the specified vaccination record.
     */
    public function show($lang ,Patient $patient, VaccinationRecord $vaccination)
    {
        $this->authorize('view', $patient);

        $vaccination->load(['vaccinationType', 'administeredBy']);

        return view('clinic.vaccinations.show', compact('patient', 'vaccination'));
    }

    /**
     * Show the form for editing the specified vaccination record.
     */
    public function edit($lang ,Patient $patient, VaccinationRecord $vaccination)
    {
        $this->authorize('update', $patient);

        $vaccinationTypes = VaccinationType::active()->ordered()->get();

        return view('clinic.vaccinations.edit', compact('patient', 'vaccination', 'vaccinationTypes'));
    }

    /**
     * Update the specified vaccination record in storage.
     */
    public function update(Request $request,$lang , Patient $patient, VaccinationRecord $vaccination)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'vaccination_type_id' => 'required|exists:vaccination_types,id',
            'vaccination_date' => 'required|date',
            'dose_number' => 'required|integer|min:1',
            'batch_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'site' => 'nullable|string|max:255',
            'reaction_notes' => 'nullable|string',
            'next_dose_due_date' => 'nullable|date',
            'status' => 'required|in:scheduled,completed,missed,cancelled',
        ]);

        $vaccination->update($validated);

        return redirect()
            ->route('patients.vaccinations.show', [$patient, $vaccination])
            ->with('success', __('translation.vaccination_updated_successfully'));
    }

    /**
     * Remove the specified vaccination record from storage.
     */
    public function destroy($lang ,Patient $patient, VaccinationRecord $vaccination)
    {
        $this->authorize('update', $patient);

        $vaccination->delete();

        return redirect()
            ->route('patients.vaccinations.index', $patient)
            ->with('success', __('translation.vaccination_deleted_successfully'));
    }

    /**
     * Display vaccination schedule.
     */
    public function schedule($lang ,Patient $patient)
    {
        $this->authorize('view', $patient);

        // Calculate patient age in months
        $ageMonths = $patient->date_of_birth ? $patient->date_of_birth->diffInMonths(now()) : 0;

        // Get recommended vaccinations based on age
        $recommendedVaccinations = VaccinationType::active()
            ->where('recommended_age_months', '<=', $ageMonths + 6) // Include vaccines due in next 6 months
            ->ordered()
            ->get();

        // Get completed vaccinations
        $completedVaccinations = VaccinationRecord::where('patient_id', $patient->id)
            ->completed()
            ->get()
            ->keyBy('vaccination_type_id');

        return view('clinic.vaccinations.schedule', compact('patient', 'recommendedVaccinations', 'completedVaccinations', 'ageMonths'));
    }
}
