<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\LabTestResult;
use App\Models\LabTestType;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabTestController extends Controller
{
    /**
     * Display a listing of lab tests for a patient.
     */
    public function index($lang ,Patient $patient)
    {
        $this->authorize('view', $patient);

        $labTests = LabTestResult::with(['labTestType', 'orderedBy'])
            ->forPatient($patient->id)
            ->latest('test_date')
            ->paginate(20);

        return view('clinic.lab-tests.index', compact('patient', 'labTests'));
    }

    /**
     * Show the form for creating a new lab test.
     */
    public function create($lang ,Patient $patient)
    {
        $this->authorize('update', $patient);

        $testTypes = LabTestType::active()->ordered()->get();

        return view('clinic.lab-tests.create', compact('patient', 'testTypes'));
    }

    /**
     * Store a newly created lab test in storage.
     */
    public function store(Request $request,$lang , Patient $patient)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'lab_test_type_id' => 'required|exists:lab_test_types,id',
            'test_date' => 'required|date',
            'result_value' => 'nullable|string',
            'result_text' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
            'lab_name' => 'nullable|string|max:255',
            'lab_reference_number' => 'nullable|string|max:255',
            'interpretation' => 'nullable|in:normal,abnormal_low,abnormal_high,critical',
            'doctor_notes' => 'nullable|string',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['ordered_by_user_id'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'completed';

        $labTest = LabTestResult::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.lab_test_added_successfully'),
                'data' => $labTest
            ]);
        }

        return redirect()
            ->route('patients.lab-tests.index', $patient)
            ->with('success', __('translation.lab_test_added_successfully'));
    }

    /**
     * Display the specified lab test.
     */
    public function show($lang ,Patient $patient, LabTestResult $labTest)
    {
        $this->authorize('view', $patient);

        $labTest->load(['labTestType', 'orderedBy', 'examination']);

        return view('clinic.lab-tests.show', compact('patient', 'labTest'));
    }

    /**
     * Show the form for editing the specified lab test.
     */
    public function edit($lang ,Patient $patient, LabTestResult $labTest)
    {
        $this->authorize('update', $patient);

        $testTypes = LabTestType::active()->ordered()->get();

        return view('clinic.lab-tests.edit', compact('patient', 'labTest', 'testTypes'));
    }

    /**
     * Update the specified lab test in storage.
     */
    public function update(Request $request,$lang , Patient $patient, LabTestResult $labTest)
    {
        $this->authorize('update', $patient);

        $validated = $request->validate([
            'lab_test_type_id' => 'required|exists:lab_test_types,id',
            'test_date' => 'required|date',
            'result_value' => 'nullable|string',
            'result_text' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
            'lab_name' => 'nullable|string|max:255',
            'lab_reference_number' => 'nullable|string|max:255',
            'interpretation' => 'nullable|in:normal,abnormal_low,abnormal_high,critical',
            'doctor_notes' => 'nullable|string',
        ]);

        $labTest->update($validated);

        return redirect()
            ->route('patients.lab-tests.show', [$patient, $labTest])
            ->with('success', __('translation.lab_test_updated_successfully'));
    }

    /**
     * Remove the specified lab test from storage.
     */
    public function destroy($lang ,Patient $patient, LabTestResult $labTest)
    {
        $this->authorize('update', $patient);

        $labTest->delete();

        return redirect()
            ->route('patients.lab-tests.index', $patient)
            ->with('success', __('translation.lab_test_deleted_successfully'));
    }

    /**
     * Display abnormal lab tests.
     */
    public function abnormal($lang ,Patient $patient)
    {
        $this->authorize('view', $patient);

        $labTests = LabTestResult::with(['labTestType', 'orderedBy'])
            ->forPatient($patient->id)
            ->abnormal()
            ->latest('test_date')
            ->paginate(20);

        return view('clinic.lab-tests.abnormal', compact('patient', 'labTests'));
    }
}
