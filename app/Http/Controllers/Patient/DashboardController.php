<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Examination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the patient dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get patient's upcoming appointments
        $upcomingAppointments = Appointment::with(['clinic.user', 'clinic.specialty'])
            ->where(function ($query) use ($user) {
                $query->where('patient_id', $user->id)
                    ->orWhere('patient_email', $user->email);
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        // Get patient's past appointments
        $pastAppointments = Appointment::with(['clinic.user', 'clinic.specialty'])
            ->where(function ($query) use ($user) {
                $query->where('patient_id', $user->id)
                    ->orWhere('patient_email', $user->email);
            })
            ->where(function ($query) {
                $query->where('appointment_date', '<', today())
                    ->orWhereIn('status', ['completed', 'cancelled', 'no_show']);
            })
            ->orderBy('appointment_date', 'desc')
            ->limit(10)
            ->get();

        // Get patient record if exists (linked to a clinic)
        $patientRecords = Patient::with(['clinic.user', 'clinic.specialty'])
            ->where('email', $user->email)
            ->orWhere('user_id', $user->id)
            ->get();

        // Get medical history (examinations)
        $examinations = Examination::with(['clinic.user', 'clinic.specialty', 'patient'])
            ->whereHas('patient', function ($query) use ($user) {
                $query->where('email', $user->email)
                    ->orWhere('user_id', $user->id);
            })
            ->orderBy('examination_date', 'desc')
            ->limit(10)
            ->get();

        // Get available clinics for booking
        $availableClinics = Clinic::with(['user', 'specialty'])
            ->approved()
            ->limit(6)
            ->get();

        // Statistics
        $stats = [
            'total_appointments' => Appointment::where(function ($query) use ($user) {
                $query->where('patient_id', $user->id)
                    ->orWhere('patient_email', $user->email);
            })->count(),
            'upcoming_appointments' => $upcomingAppointments->count(),
            'total_examinations' => $examinations->count(),
            'clinics_visited' => $patientRecords->count(),
        ];

        return view('patient.dashboard', compact(
            'upcomingAppointments',
            'pastAppointments',
            'patientRecords',
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
     * Browse available clinics.
     */
    public function clinics(Request $request)
    {
        $query = Clinic::with(['user', 'specialty'])
            ->approved()
            ->withCount('patients');

        // Filter by specialty
        if ($request->filled('specialty')) {
            $query->where('specialty_id', $request->specialty);
        }

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $clinics = $query->paginate(12);

        $specialties = \App\Models\Specialty::active()
            ->whereHas('clinics', function ($q) {
                $q->approved();
            })
            ->ordered()
            ->get();

        return view('patient.clinics', compact('clinics', 'specialties'));
    }

    /**
     * Show clinic details and booking form.
     */
    public function showClinic(string $locale, Clinic $clinic)
    {
        if (!$clinic->isApproved()) {
            abort(404);
        }

        $clinic->load(['user', 'specialty']);

        // Get clinic's available time slots (simplified - you can make this more complex)
        $availableDates = collect();
        for ($i = 1; $i <= 14; $i++) {
            $date = now()->addDays($i);
            if (!$date->isWeekend()) { // Skip weekends
                $availableDates->push($date->format('Y-m-d'));
            }
        }

        return view('patient.clinic-details', compact('clinic', 'availableDates'));
    }

    /**
     * Book an appointment.
     */
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'reason' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $clinic = Clinic::findOrFail($request->clinic_id);

        if (!$clinic->isApproved()) {
            return back()->with('error', __('translation.patient.clinic_not_available'));
        }

        // Check for existing appointment at same time
        $existingAppointment = Appointment::where('clinic_id', $clinic->id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->exists();

        if ($existingAppointment) {
            return back()->with('error', __('translation.patient.time_slot_taken'));
        }

        $appointment = Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $user->id,
            'patient_name' => $user->name,
            'patient_phone' => $user->phone,
            'patient_email' => $user->email,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('patient.dashboard')
            ->with('success', __('translation.patient.appointment_booked'));
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
