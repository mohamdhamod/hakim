<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Examination;
use App\Models\User;
use App\Services\PatientIntegrationService;
use App\Traits\ClinicAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
{
    use ClinicAuthorization;

    protected PatientIntegrationService $integrationService;

    public function __construct(PatientIntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $clinic = $this->authorizeClinicAccess();

        if ($request->ajax()) {
            $patients = Patient::with(['latestExamination'])
                ->withCount('examinations')
                ->where('clinic_id', $clinic->id)
                ->select('patients.*');

            return DataTables::of($patients)
                ->addColumn('age', function ($patient) {
                    return $patient->age ?? '-';
                })
                ->addColumn('gender_display', function ($patient) {
                    return $patient->gender_label;
                })
                ->addColumn('last_visit', function ($patient) {
                    $lastExam = $patient->latestExamination;
                    return $lastExam ? $lastExam->examination_date->format('Y-m-d') : '-';
                })
                ->addColumn('examinations_count', function ($patient) {
                    return $patient->examinations_count ?? 0;
                })
                ->addColumn('actions', function ($patient) {
                    return '
                        <div class="btn-group">
                            <a href="' . route('clinic.patients.show', $patient->id) . '" class="btn btn-sm btn-info" title="' . __('translation.common.view') . '">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="' . route('clinic.patients.edit', $patient->id) . '" class="btn btn-sm btn-warning" title="' . __('translation.common.edit') . '">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="' . route('clinic.patients.show', $patient->id) . '" class="btn btn-sm btn-success" title="' . __('translation.examination.new') . '">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $patients = Patient::where('clinic_id', $clinic->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('clinic.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $clinic = $this->authorizeClinicAccess();

        $fileNumber = Patient::generateFileNumber($clinic->id);

        return view('clinic.patients.create', compact('fileNumber'));
    }

    /**
     * Store a newly created patient.
     */
    public function store(Request $request)
    {
        $clinic = $this->authorizeClinicAccess(true);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_year' => 'nullable|integer|min:1920|max:' . date('Y'),
            'birth_month' => 'nullable|integer|min:1|max:12',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
        ]);

        // Convert birth_year and birth_month to date_of_birth
        if (!empty($validated['birth_year'])) {
            $month = $validated['birth_month'] ?? 1;
            $validated['date_of_birth'] = $validated['birth_year'] . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        }
        unset($validated['birth_year'], $validated['birth_month']);

        // Check for potential duplicates
        $duplicates = $this->integrationService->findPotentialDuplicates($clinic, $validated);
        
        if (!empty($duplicates) && !$request->boolean('confirm_duplicate')) {
            return response()->json([
                'success' => false,
                'requires_confirmation' => true,
                'message' => __('translation.patient.potential_duplicate_found'),
                'duplicates' => collect($duplicates)->map(fn($d) => [
                    'id' => $d['patient']->id,
                    'name' => $d['patient']->full_name,
                    'file_number' => $d['patient']->file_number,
                    'phone' => $d['patient']->phone,
                    'match_type' => $d['match_type'],
                    'confidence' => $d['confidence'],
                ])->toArray(),
            ], 200); // Return 200 so handleSubmit processes it correctly
        }

        $validated['clinic_id'] = $clinic->id;
        $validated['file_number'] = $this->integrationService->generateFileNumber($clinic);

        // Try to link with existing user account
        if (!empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
            if ($user && $user->isPatient()) {
                $validated['user_id'] = $user->id;
            }
        }

        $patient = Patient::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.patient.created_successfully'),
                'redirect' => route('clinic.patients.show', $patient->file_number),
            ], 200);
        }

        return redirect()->route('clinic.patients.show', $patient->file_number)
            ->with('success', __('translation.patient.created_successfully'));
    }

    /**
     * Display the specified patient.
     */
    public function show($lang , Patient $patient)
    {
        $clinic = $this->authorizePatientAccess($patient);

        $patient->load([
            'examinations' => function ($query) {
                $query->orderBy('examination_date', 'desc');
            },
            'labTestResults' => function ($query) {
                $query->with('labTestType.translations')->latest('test_date')->limit(10);
            },
            'vaccinationRecords' => function ($query) {
                $query->with('vaccinationType.translations')->latest('vaccination_date')->limit(10);
            },
            'growthMeasurements' => function ($query) {
                $query->latest('measurement_date')->limit(10);
            },
            'chronicDiseases' => function ($query) {
                $query->with(['chronicDiseaseType.translations', 'monitoringRecords' => function ($q) {
                    $q->latest('monitoring_date')->limit(20);
                }])->where('status', '!=', 'resolved');
            }
        ]);

        // Generate examination number for the modal form
        $examinationNumber = Examination::generateExaminationNumber($clinic->id);

        return view('clinic.patients.show', compact('patient', 'examinationNumber'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit($lang , Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        return view('clinic.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient.
     */
    public function update(Request $request, $lang , Patient $patient)
    {
        $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'birth_year' => 'nullable|integer|min:1920|max:' . date('Y'),
            'birth_month' => 'nullable|integer|min:1|max:12',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string|max:1000',
            'chronic_diseases' => 'nullable|string|max:1000',
            'medical_history' => 'nullable|string|max:2000',
            'family_history' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Convert birth_year and birth_month to date_of_birth
        if (!empty($validated['birth_year'])) {
            $month = $validated['birth_month'] ?? 1;
            $validated['date_of_birth'] = $validated['birth_year'] . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        } else {
            $validated['date_of_birth'] = null;
        }
        unset($validated['birth_year'], $validated['birth_month']);

        $patient->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.patient.updated_successfully'),
                'patient' => $patient,
                'redirect' => route('clinic.patients.show', $patient->file_number),
            ]);
        }

        return redirect()->route('clinic.patients.show', $patient->file_number)
            ->with('success', __('translation.patient.updated_successfully'));
    }

    /**
     * Remove the specified patient.
     */
    public function destroy($lang , Patient $patient)
    {
        $this->authorizePatientAccess($patient, true);

        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => __('translation.patient.deleted_successfully'),
        ]);
    }

    /**
     * Search patients.
     */
    public function search(Request $request)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic) {
            return response()->json([]);
        }

        $term = $request->get('term', '');
        
        $patients = Patient::where('clinic_id', $clinic->id)
            ->search($term)
            ->limit(10)
            ->get(['id', 'file_number', 'full_name', 'phone']);

        return response()->json($patients);
    }

    /**
     * Update medical history.
     */
    public function updateMedicalHistory(Request $request, $lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'allergies' => 'nullable|string|max:2000',
            'chronic_diseases' => 'nullable|string|max:2000',
            'medical_history' => 'nullable|string|max:5000',
            'family_history' => 'nullable|string|max:2000',
        ]);

        $patient->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.patient.medical_history_updated'),
            ]);
        }

        return redirect()->route('clinic.patients.show', $patient->id)
            ->with('success', __('translation.patient.medical_history_updated'));
    }

    /**
     * Update emergency contact.
     */
    public function updateEmergencyContact(Request $request, $lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $patient->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.patient.emergency_contact_updated'),
            ]);
        }

        return redirect()->route('clinic.patients.show', $patient->id)
            ->with('success', __('translation.patient.emergency_contact_updated'));
    }

    /**
     * Update notes.
     */
    public function updateNotes(Request $request, $lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient, true);

        $validated = $request->validate([
            'notes' => 'nullable|string|max:5000',
        ]);

        $patient->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.patient.notes_updated'),
            ]);
        }

        return redirect()->route('clinic.patients.show', $patient->id)
            ->with('success', __('translation.patient.notes_updated'));
    }

    /**
     * Display all examinations for a patient.
     */
    public function allExaminations($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $examinations = $patient->examinations()
            ->orderBy('examination_date', 'desc')
            ->paginate(15);

        return view('clinic.patients.partials.all-examinations', compact('patient', 'examinations'));
    }

    /**
     * Display all lab tests for a patient.
     */
    public function allLabTests($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $labTests = $patient->labTestResults()
            ->with('labTestType.translations')
            ->orderBy('test_date', 'desc')
            ->paginate(15);

        return view('clinic.patients.partials.all-lab-tests', compact('patient', 'labTests'));
    }

    /**
     * Display all vaccinations for a patient.
     */
    public function allVaccinations($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $vaccinations = $patient->vaccinationRecords()
            ->with('vaccinationType.translations')
            ->orderBy('vaccination_date', 'desc')
            ->paginate(15);

        return view('clinic.patients.partials.all-vaccinations', compact('patient', 'vaccinations'));
    }

    /**
     * Display all chronic diseases for a patient.
     */
    public function allChronicDiseases($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $chronicDiseases = $patient->chronicDiseases()
            ->with('chronicDiseaseType.translations')
            ->orderBy('diagnosis_date', 'desc')
            ->paginate(15);

        return view('clinic.patients.partials.all-chronic-diseases', compact('patient', 'chronicDiseases'));
    }

    /**
     * Display all growth measurements for a patient.
     */
    public function allGrowthMeasurements($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $measurements = $patient->growthMeasurements()
            ->orderBy('measurement_date', 'desc')
            ->paginate(15);

        return view('clinic.patients.partials.all-growth-measurements', compact('patient', 'measurements'));
    }
}
