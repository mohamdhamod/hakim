<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\ExaminationAttachment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ExaminationController extends Controller
{
    /**
     * Get the current doctor's clinic.
     */
    protected function getClinic()
    {
        return Auth::user()->clinic;
    }

    /**
     * Display a listing of examinations.
     */
    public function index(Request $request)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || !$clinic->isApproved()) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('translation.clinic.not_approved'));
        }

        if ($request->ajax()) {
            $examinations = Examination::with(['patient'])
                ->where('clinic_id', $clinic->id)
                ->select('examinations.*');

            return DataTables::of($examinations)
                ->addColumn('patient_name', function ($exam) {
                    return $exam->patient->full_name ?? '-';
                })
                ->addColumn('patient_file_number', function ($exam) {
                    return $exam->patient->file_number ?? '-';
                })
                ->addColumn('examination_date_formatted', function ($exam) {
                    return $exam->examination_date->format('Y-m-d H:i');
                })
                ->addColumn('status_badge', function ($exam) {
                    return '<span class="badge ' . $exam->status_badge_class . '">' . $exam->status_label . '</span>';
                })
                ->addColumn('actions', function ($exam) {
                    return '
                        <div class="btn-group">
                            <a href="' . route('clinic.examinations.show', $exam->id) . '" class="btn btn-sm btn-info" title="' . __('translation.common.view') . '">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="' . route('clinic.examinations.edit', $exam->id) . '" class="btn btn-sm btn-warning" title="' . __('translation.common.edit') . '">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="' . route('clinic.examinations.print', $exam->id) . '" class="btn btn-sm btn-secondary" target="_blank" title="' . __('translation.common.print') . '">
                                <i class="bi bi-printer"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['status_badge', 'actions'])
                ->make(true);
        }

        $examinations = Examination::with(['patient'])
            ->where('clinic_id', $clinic->id)
            ->orderBy('examination_date', 'desc')
            ->paginate(15);

        return view('clinic.examinations.index', compact('examinations'));
    }

    /**
     * Display today's examinations.
     */
    public function today(Request $request)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || !$clinic->isApproved()) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('translation.clinic.not_approved'));
        }

        $examinations = Examination::with(['patient'])
            ->where('clinic_id', $clinic->id)
            ->today()
            ->orderBy('examination_date')
            ->get();

        return view('clinic.examinations.today', compact('examinations'));
    }

    /**
     * Store a newly created examination.
     */
    public function store(Request $request)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || !$clinic->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => __('translation.clinic.not_approved'),
            ], 403);
        }

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
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
        ]);

        // Verify patient belongs to this clinic
        $patient = Patient::findOrFail($validated['patient_id']);
        if ($patient->clinic_id !== $clinic->id) {
            abort(403);
        }

        $validated['clinic_id'] = $clinic->id;
        $validated['user_id'] = Auth::id();
        $validated['examination_number'] = Examination::generateExaminationNumber($clinic->id);
        $validated['status'] = $validated['status'] ?? 'scheduled';

        $examination = Examination::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.examination.created_successfully'),
                'examination' => $examination,
                'redirect' => route('clinic.examinations.show', $examination->id),
            ]);
        }

        return redirect()->route('clinic.examinations.show', $examination->id)
            ->with('success', __('translation.examination.created_successfully'));
    }

    /**
     * Display the specified examination.
     */
    public function show($lang ,Examination $examination)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || $examination->clinic_id !== $clinic->id) {
            abort(403);
        }

        $examination->load(['patient', 'attachments']);

        return view('clinic.examinations.show', compact('examination'));
    }

    /**
     * Show the form for editing the specified examination.
     */
    public function edit($lang , Examination $examination)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || $examination->clinic_id !== $clinic->id) {
            abort(403);
        }

        $examination->load(['patient']);

        return view('clinic.examinations.edit', compact('examination'));
    }

    /**
     * Update the specified examination.
     */
    public function update(Request $request, $lang , Examination $examination)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || $examination->clinic_id !== $clinic->id) {
            abort(403);
        }

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
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
        ]);

        $examination->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('translation.examination.updated_successfully'),
                'examination' => $examination,
                'redirect' => route('clinic.examinations.show', $examination->id),
            ]);
        }

        return redirect()->route('clinic.examinations.show', $examination->id)
            ->with('success', __('translation.examination.updated_successfully'));
    }

    /**
     * Print examination report.
     */
    public function print($lang , Examination $examination)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || $examination->clinic_id !== $clinic->id) {
            abort(403);
        }

        $examination->load(['patient', 'clinic', 'doctor']);

        return view('clinic.examinations.print', compact('examination'));
    }

    /**
     * Upload attachment to examination.
     */
    public function uploadAttachment(Request $request, $lang , Examination $examination)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || $examination->clinic_id !== $clinic->id) {
            abort(403);
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
            'description' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $path = $file->store('examinations/' . $examination->id, 'public');

        $attachment = ExaminationAttachment::create([
            'examination_id' => $examination->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $this->getFileType($file->getMimeType()),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('translation.examination.attachment_uploaded'),
            'attachment' => $attachment,
        ]);
    }

    /**
     * Delete attachment.
     */
    public function deleteAttachment($lang , ExaminationAttachment $attachment)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || $attachment->examination->clinic_id !== $clinic->id) {
            abort(403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => __('translation.examination.attachment_deleted'),
        ]);
    }

    /**
     * Get file type from mime type.
     */
    private function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if ($mimeType === 'application/pdf') {
            return 'pdf';
        }
        return 'document';
    }

    /**
     * Mark examination as completed.
     */
    public function complete($lang , Examination $examination)
    {
        $clinic = $this->getClinic();
        
        if (!$clinic || $examination->clinic_id !== $clinic->id) {
            abort(403);
        }

        $examination->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => __('translation.examination.marked_completed'),
        ]);
    }
}
