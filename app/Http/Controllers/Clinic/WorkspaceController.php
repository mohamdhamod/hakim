<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Examination;
use App\Models\Patient;
use App\Services\PatientIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    /**
     * Display the chat-style workspace.
     */
    public function index()
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        // Check if user is a doctor
        if (!$user->isDoctor()) {
            return redirect()->route('home')
                ->with('error', __('translation.clinic.doctors_only'));
        }

        // Check if clinic exists
        if (!$clinic) {
            return view('clinic.no-clinic');
        }

        // Check approval status
        if ($clinic->isPending()) {
            return view('clinic.pending-approval', compact('clinic'));
        }

        if ($clinic->status === 'rejected') {
            return view('clinic.rejected', compact('clinic'));
        }

        // Get today's appointments (limit to 5 for workspace)
        $todayAppointments = Appointment::with('patient')
            ->where('clinic_id', $clinic->id)
            ->ordered()
            ->limit(5)
            ->get();

        // Get upcoming appointments (excluding today, limit to 5)
        $upcomingAppointments = Appointment::with('patient')
            ->where('clinic_id', $clinic->id)
            ->where('appointment_date', '>', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->ordered()
            ->limit(5)
            ->get();

        // Get pending appointments (limit to 5)
        $pendingAppointments = Appointment::with('patient')
            ->where('clinic_id', $clinic->id)
            ->where('status', 'pending')
            ->limit(5)
            ->get();

        // Get recent patients (limit to 5 for workspace)
        $patients = Patient::where('clinic_id', $clinic->id)
            ->withCount('examinations')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get counts for "View All" buttons
        $totalPatients = Patient::where('clinic_id', $clinic->id)->count();
        $totalTodayAppointments = Appointment::where('clinic_id', $clinic->id)->today()->count();
        $totalUpcomingAppointments = Appointment::where('clinic_id', $clinic->id)
            ->where('appointment_date', '>', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
        $totalPendingAppointments = Appointment::where('clinic_id', $clinic->id)
            ->where('status', 'pending')
            ->count();

        // Get total examinations
        $totalExaminations = Examination::where('clinic_id', $clinic->id)->count();

        return view('clinic.workspace', compact(
            'clinic',
            'todayAppointments',
            'upcomingAppointments',
            'pendingAppointments',
            'patients',
            'totalExaminations',
            'totalPatients',
            'totalTodayAppointments',
            'totalUpcomingAppointments',
            'totalPendingAppointments'
        ));
    }

    /**
     * Get appointment details for the workspace.
     */
    public function appointmentDetails($lang, Appointment $appointment)
    {
        $clinic = Auth::user()->clinic;

        if ($appointment->clinic_id !== $clinic->id) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.not_found'),
            ], 404);
        }

        $appointment->load(['patient', 'clinic']);

        $html = view('clinic.partials.appointment-details', compact('appointment'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'title' => $appointment->patient_display_name,
            'subtitle' => $appointment->appointment_date->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i'),
        ]);
    }

    /**
     * Get patient details for the workspace.
     */
    public function patientDetails($lang , Patient $patient)
    {
        $clinic = Auth::user()->clinic;

        if ($patient->clinic_id !== $clinic->id) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.not_found'),
            ], 404);
        }

        $patient->load(['examinations' => function ($query) {
            $query->orderBy('examination_date', 'desc')->limit(10);
        }]);

        $html = view('clinic.partials.patient-details', compact('patient'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'title' => $patient->name,
            'subtitle' => __('translation.patient.file_number') . ': ' . $patient->file_number,
        ]);
    }

    /**
     * Confirm an appointment.
     * This also links/creates a patient record for the appointment.
     */
    public function confirmAppointment($lang, Appointment $appointment, PatientIntegrationService $integrationService)
    {
        $clinic = Auth::user()->clinic;

        if ($appointment->clinic_id !== $clinic->id) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.not_found'),
            ], 403);
        }

        // Link or create patient record
        $patient = $integrationService->linkAppointmentToPatient($appointment);
        
        // Confirm the appointment
        $appointment->confirm();

        return response()->json([
            'success' => true,
            'message' => __('translation.clinic_chat.appointment_confirmed'),
            'patient' => [
                'id' => $patient->id,
                'file_number' => $patient->file_number,
                'is_new' => $appointment->is_new_patient,
            ],
        ]);
    }

    /**
     * Cancel an appointment from doctor side.
     */
    public function cancelAppointment($lang, Request $request, Appointment $appointment)
    {
        $clinic = Auth::user()->clinic;

        if ($appointment->clinic_id !== $clinic->id) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.not_found'),
            ], 403);
        }

        $appointment->cancel($request->cancellation_reason);

        return response()->json([
            'success' => true,
            'message' => __('translation.clinic_chat.appointment_cancelled'),
        ]);
    }

    /**
     * Complete an appointment.
     */
    public function completeAppointment($lang, Request $request, Appointment $appointment)
    {
        $clinic = Auth::user()->clinic;

        if ($appointment->clinic_id !== $clinic->id) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.not_found'),
            ], 403);
        }

        // Update notes if provided
        if ($request->has('notes') && $request->notes) {
            $appointment->notes = $request->notes;
            $appointment->save();
        }

        $appointment->complete();

        $response = [
            'success' => true,
            'message' => __('translation.clinic_chat.appointment_completed'),
        ];

        // If create examination is requested and patient exists
        if ($request->boolean('create_examination') && $appointment->patient_id) {
            $response['examination_url'] = route('clinic.examinations.create', ['patient' => $appointment->patient_id]);
        }

        return response()->json($response);
    }

    /**
     * Register patient from appointment data.
     */
    public function registerPatientFromAppointment($lang, Request $request, Appointment $appointment, PatientIntegrationService $integrationService)
    {
        $clinic = Auth::user()->clinic;

        if ($appointment->clinic_id !== $clinic->id) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.not_found'),
            ], 403);
        }

        // Check if already has a patient
        if ($appointment->patient_id || $appointment->clinic_patient_id) {
            return response()->json([
                'success' => false,
                'message' => __('translation.clinic_chat.patient_already_registered'),
            ]);
        }

        // Build date_of_birth from birth_year and birth_month
        $dateOfBirth = null;
        if ($request->birth_year) {
            $month = $request->birth_month ?? 1;
            $dateOfBirth = $request->birth_year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        }

        // Create patient from appointment
        $patient = Patient::create([
            'clinic_id' => $clinic->id,
            'full_name' => $appointment->patient_name,
            'phone' => $appointment->patient_phone,
            'email' => $appointment->patient_email,
            'date_of_birth' => $dateOfBirth,
            'gender' => $request->gender,
            'notes' => $request->notes,
        ]);

        // Link appointment to patient
        $appointment->update([
            'clinic_patient_id' => $patient->id,
            'linked_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('translation.clinic_chat.patient_registered'),
            'patient_url' => route('clinic.patients.show', $patient->id),
        ]);
    }

    /**
     * Display all appointments for the clinic.
     */
    public function allAppointments(Request $request)
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        // Build query with filters
        $query = Appointment::with(['patient', 'clinicPatient'])
            ->where('clinic_id', $clinic->id);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Search by patient name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('patient_phone', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $appointments = $query->ordered()->paginate(15)->withQueryString();

        // Get counts for stats
        $totalAppointments = Appointment::where('clinic_id', $clinic->id)->count();
        $pendingCount = Appointment::where('clinic_id', $clinic->id)->where('status', 'pending')->count();
        $confirmedCount = Appointment::where('clinic_id', $clinic->id)->where('status', 'confirmed')->count();
        $completedCount = Appointment::where('clinic_id', $clinic->id)->where('status', 'completed')->count();
        $cancelledCount = Appointment::where('clinic_id', $clinic->id)->where('status', 'cancelled')->count();

        return view('clinic.appointments.index', compact(
            'clinic',
            'appointments',
            'totalAppointments',
            'pendingCount',
            'confirmedCount',
            'completedCount',
            'cancelledCount'
        ));
    }
}
