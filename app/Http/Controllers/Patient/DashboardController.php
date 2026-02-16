<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Examination;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the patient dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        $patientScope = fn ($query) => $query->where('patient_id', $user->id)
            ->orWhere('patient_email', $user->email);

        $patientRecordScope = fn ($query) => $query->where('email', $user->email)
            ->orWhere('user_id', $user->id);

        // Get patient's upcoming appointments
        $upcomingAppointments = Appointment::with(['clinic.user', 'clinic.specialty'])
            ->where($patientScope)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        // Get recent examinations
        $examinations = Examination::with(['clinic.user'])
            ->whereHas('patient', $patientRecordScope)
            ->orderBy('examination_date', 'desc')
            ->limit(5)
            ->get();

        // Get available clinics for booking
        $availableClinics = Clinic::with(['user', 'specialty'])
            ->approved()
            ->limit(6)
            ->get();

        // Statistics
        $stats = [
            'total_appointments' => Appointment::where($patientScope)->count(),
            'upcoming_appointments' => $upcomingAppointments->count(),
            'total_examinations' => Examination::whereHas('patient', $patientRecordScope)->count(),
            'clinics_visited' => Patient::where($patientRecordScope)->count(),
        ];

        return view('patient.dashboard', compact(
            'upcomingAppointments',
            'examinations',
            'availableClinics',
            'stats'
        ));
    }

    /**
     * Show all appointments for the patient.
     */
    public function appointments()
    {
        $user = Auth::user();

        $appointments = Appointment::with(['clinic.user', 'clinic.specialty'])
            ->where(function ($query) use ($user) {
                $query->where('patient_id', $user->id)
                    ->orWhere('patient_email', $user->email);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('patient.appointments', compact('appointments'));
    }

    /**
     * Show medical history for the patient.
     */
    public function medicalHistory()
    {
        $user = Auth::user();

        $examinations = Examination::with(['clinic.user', 'clinic.specialty', 'patient', 'attachments'])
            ->whereHas('patient', function ($query) use ($user) {
                $query->where('email', $user->email)
                    ->orWhere('user_id', $user->id);
            })
            ->orderBy('examination_date', 'desc')
            ->paginate(15);

        return view('patient.medical-history', compact('examinations'));
    }

    /**
     * Cancel an appointment.
     */
    public function cancelAppointment(string $locale, Appointment $appointment)
    {
        $user = Auth::user();

        // Check ownership
        if ($appointment->patient_id !== $user->id && $appointment->patient_email !== $user->email) {
            abort(403);
        }

        // Can only cancel pending or confirmed appointments
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', __('translation.patient.cannot_cancel'));
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => __('translation.patient.cancelled_by_patient'),
        ]);

        return back()->with('success', __('translation.patient.appointment_cancelled'));
    }
}
