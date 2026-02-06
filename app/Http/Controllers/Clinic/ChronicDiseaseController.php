<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ChronicDiseaseMonitoring;
use App\Models\ChronicDiseaseType;
use App\Models\Patient;
use App\Models\PatientChronicDisease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChronicDiseaseController extends Controller
{
    /**
     * Display a listing of chronic diseases for a patient.
     */
    public function index(Patient $patient)
    {
        $this->authorize('view', $patient);

        $chronicDiseases = PatientChronicDisease::with(['chronicDiseaseType', 'diagnosedBy'])
            ->where('patient_id', $patient->id)
            ->get();

        return view('clinic.chronic-diseases.index', compact('patient', 'chronicDiseases'));
    }

    /**
     * Show the form for creating a new chronic disease.
     */
    public function create(Patient $patient)
    {
        $this->authorize('update', $patient);

        $diseaseTypes = ChronicDiseaseType::active()->get();

        return view('clinic.chronic-diseases.create', compact('patient', 'diseaseTypes'));
    }

    /**
     * Store a newly created chronic disease in storage.
     */
    public function store(Request $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'chronic_disease_type_id' => 'required|exists:chronic_disease_types,id',
            'diagnosis_date' => 'required|date',
            'severity' => 'nullable|in:mild,moderate,severe',
            'status' => 'required|in:active,in_remission,resolved',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['diagnosed_by_user_id'] = Auth::id();

        PatientChronicDisease::create($validated);

        return redirect()
            ->route('patients.chronic-diseases.index', $patient)
            ->with('success', __('translation.chronic_disease_added_successfully'));
    }

    /**
     * Display the specified chronic disease.
     */
    public function show(Patient $patient, PatientChronicDisease $chronicDisease)
    {
        $this->authorize('view', $patient);

        $chronicDisease->load(['chronicDiseaseType', 'diagnosedBy', 'monitoringRecords']);

        return view('clinic.chronic-diseases.show', compact('patient', 'chronicDisease'));
    }

    /**
     * Show the form for editing the specified chronic disease.
     */
    public function edit(Patient $patient, PatientChronicDisease $chronicDisease)
    {
        $this->authorize('update', $patient);

        $diseaseTypes = ChronicDiseaseType::active()->get();

        return view('clinic.chronic-diseases.edit', compact('patient', 'chronicDisease', 'diseaseTypes'));
    }

    /**
     * Update the specified chronic disease in storage.
     */
    public function update(Request $request, Patient $patient, PatientChronicDisease $chronicDisease)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'chronic_disease_type_id' => 'required|exists:chronic_disease_types,id',
            'diagnosis_date' => 'required|date',
            'severity' => 'nullable|in:mild,moderate,severe',
            'status' => 'required|in:active,in_remission,resolved',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'last_followup_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
        ]);

        $chronicDisease->update($validated);

        return redirect()
            ->route('patients.chronic-diseases.show', [$patient, $chronicDisease])
            ->with('success', __('translation.chronic_disease_updated_successfully'));
    }

    /**
     * Remove the specified chronic disease from storage.
     */
    public function destroy(Patient $patient, PatientChronicDisease $chronicDisease)
    {
        $this->authorize('update', $patient);

        $chronicDisease->delete();

        return redirect()
            ->route('patients.chronic-diseases.index', $patient)
            ->with('success', __('translation.chronic_disease_deleted_successfully'));
    }

    /**
     * Store a new monitoring record.
     */
    public function storeMonitoring(Request $request, Patient $patient, PatientChronicDisease $chronicDisease)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'monitoring_date' => 'required|date',
            'parameter_name' => 'required|string|max:255',
            'parameter_value' => 'required|string|max:255',
            'parameter_unit' => 'nullable|string|max:50',
            'status' => 'nullable|in:controlled,uncontrolled,critical',
            'notes' => 'nullable|string',
        ]);

        $validated['patient_chronic_disease_id'] = $chronicDisease->id;
        $validated['recorded_by_user_id'] = Auth::id();

        ChronicDiseaseMonitoring::create($validated);

        // Update last follow-up date
        $chronicDisease->update([
            'last_followup_date' => $validated['monitoring_date'],
        ]);

        return redirect()
            ->route('patients.chronic-diseases.show', [$patient, $chronicDisease])
            ->with('success', __('translation.monitoring_record_added_successfully'));
    }

    /**
     * Display overdue follow-ups dashboard.
     */
    public function overdueFollowups()
    {
        $user = Auth::user();
        
        // Get patients from user's clinic
        $patientIds = Patient::where('clinic_id', $user->clinic_id)->pluck('id');

        $overdueDiseases = PatientChronicDisease::with(['patient', 'chronicDiseaseType'])
            ->whereIn('patient_id', $patientIds)
            ->overdueFollowup()
            ->get();

        return view('clinic.chronic-diseases.overdue', compact('overdueDiseases'));
    }
}
