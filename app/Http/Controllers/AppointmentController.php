<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Store a new appointment.
     */
    public function store(Request $request)
    {
        $rules = [
            'clinic_id' => ['required', 'exists:clinics,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];

        // Guest booking validation
        if (!Auth::check()) {
            $rules['patient_name'] = ['required', 'string', 'max:255'];
            $rules['patient_phone'] = ['required', 'string', 'max:20'];
            $rules['patient_email'] = ['nullable', 'email', 'max:255'];
        }

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $clinic = Clinic::findOrFail($request->clinic_id);
        
        // Check if clinic is approved
        if ($clinic->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => __('translation.clinic_home.clinic_not_available'),
            ], 422);
        }

        $appointmentData = [
            'clinic_id' => $clinic->id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ];

        if (Auth::check()) {
            $appointmentData['patient_id'] = Auth::id();
            $appointmentData['patient_name'] = Auth::user()->name;
            $appointmentData['patient_phone'] = Auth::user()->phone;
            $appointmentData['patient_email'] = Auth::user()->email;
        } else {
            $appointmentData['patient_name'] = $request->patient_name;
            $appointmentData['patient_phone'] = $request->patient_phone;
            $appointmentData['patient_email'] = $request->patient_email;
        }

        $appointment = Appointment::create($appointmentData);

        return response()->json([
            'success' => true,
            'message' => __('translation.clinic_home.booking_success'),
            'appointment' => $appointment,
        ]);
    }

    /**
     * Get user's appointments.
     */
    public function myAppointments()
    {
        $appointments = Appointment::with(['clinic', 'clinic.doctor', 'clinic.specialty'])
            ->where('patient_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        // Check ownership
        if ($appointment->patient_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('translation.common.error_occurred'),
            ], 403);
        }

        // Can only cancel pending or confirmed appointments
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => __('translation.clinic_home.cannot_cancel'),
            ], 422);
        }

        $appointment->cancel($request->reason);

        return response()->json([
            'success' => true,
            'message' => __('translation.clinic_home.appointment_cancelled'),
        ]);
    }
}
