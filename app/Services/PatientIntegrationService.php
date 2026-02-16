<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PatientIntegrationService
 * 
 * Handles the integration between:
 * - User accounts (patients who register themselves)
 * - Patient records (clinic's internal patient database)
 * - Appointments (booking system)
 * 
 * Patient matching is done by phone number ONLY.
 * 
 * Flow scenarios:
 * 1. Registered user books appointment → Link to existing or create new Patient record (by phone)
 * 2. Guest books appointment → Create Patient record when appointment is confirmed
 * 3. Clinic creates patient directly → Patient record without user account
 * 4. User registers with existing patient data → Link to existing Patient records (by phone)
 */
class PatientIntegrationService
{
    /**
     * Find or create a patient record for an appointment.
     * Called when an appointment is confirmed by the clinic.
     */
    public function linkAppointmentToPatient(Appointment $appointment): Patient
    {
        return DB::transaction(function () use ($appointment) {
            // Try to find existing patient
            $patient = $this->findExistingPatient($appointment);
            
            if (!$patient) {
                // Create new patient record
                $patient = $this->createPatientFromAppointment($appointment);
            }
            
            // Link appointment to patient
            $appointment->update([
                'clinic_patient_id' => $patient->id,
                'is_new_patient' => $this->isNewPatient($patient, $appointment->clinic_id),
                'linked_at' => now(),
            ]);
            
            return $patient;
        });
    }

    /**
     * Find existing patient by phone number only.
     */
    public function findExistingPatient(Appointment $appointment): ?Patient
    {
        $clinic = $appointment->clinic;
        
        // Find by phone number only
        $phone = $appointment->patient?->phone ?? $appointment->patient_phone;
        if ($phone) {
            $patient = Patient::forClinic($clinic->id)
                ->where('phone', $this->normalizePhone($phone))
                ->first();
            
            if ($patient) {
                // Link user if not already linked
                if ($appointment->patient_id && !$patient->user_id) {
                    $patient->update(['user_id' => $appointment->patient_id]);
                }
                return $patient;
            }
        }
        
        return null;
    }

    /**
     * Create a new patient record from appointment data.
     */
    public function createPatientFromAppointment(Appointment $appointment): Patient
    {
        $clinic = $appointment->clinic;
        $user = $appointment->patient;
        
        $patient = Patient::create([
            'user_id' => $appointment->patient_id,
            'file_number' => $this->generateFileNumber($clinic),
            'full_name' => $user?->name ?? $appointment->patient_name,
            'phone' => $user?->phone ?? $appointment->patient_phone,
            'email' => $user?->email ?? $appointment->patient_email,
        ]);

        $patient->clinics()->attach($clinic->id);

        return $patient;
    }

    /**
     * Link a user account to existing patient records across all clinics.
     * Called when a user registers or logs in.
     */
    public function linkUserToPatientRecords(User $user): int
    {
        $linkedCount = 0;
        
        // Find patient records with matching phone only
        if ($user->phone) {
            $patients = Patient::whereNull('user_id')
                ->where('phone', $this->normalizePhone($user->phone))
                ->get();
            
            foreach ($patients as $patient) {
                $patient->update(['user_id' => $user->id]);
                $linkedCount++;
            }
            
            // Also update appointments by phone
            Appointment::whereNull('patient_id')
                ->where('patient_phone', $this->normalizePhone($user->phone))
                ->update(['patient_id' => $user->id]);
        }
        
        return $linkedCount;
    }

    /**
     * Check if this is a new patient (first visit to this clinic).
     */
    public function isNewPatient(Patient $patient, int $clinicId): bool
    {
        // Check if patient has any completed examinations
        return !$patient->examinations()
            ->where('clinic_id', $clinicId)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Generate a unique file number for a patient in a clinic.
     * Delegates to Patient::generateFileNumber().
     */
    public function generateFileNumber(Clinic $clinic): string
    {
        return Patient::generateFileNumber($clinic->id);
    }

    /**
     * Get patient's appointment history across clinics.
     */
    public function getPatientAppointmentHistory(User $user): array
    {
        $appointments = Appointment::where('patient_id', $user->id)
            ->with(['clinic', 'clinicPatient'])
            ->orderBy('appointment_date', 'desc')
            ->get();
        
        return [
            'total' => $appointments->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'upcoming' => $appointments->where('status', 'confirmed')
                ->where('appointment_date', '>=', now()->toDateString())
                ->count(),
            'appointments' => $appointments,
        ];
    }

    /**
     * Get all patient records linked to a user.
     */
    public function getUserPatientRecords(User $user): array
    {
        $patients = Patient::where('user_id', $user->id)
            ->with(['clinics', 'examinations'])
            ->get();
        
        return $patients->map(function ($patient) {
            return [
                'patient' => $patient,
                'clinics' => $patient->clinics,
                'examinations_count' => $patient->examinations->count(),
                'last_visit' => $patient->examinations->sortByDesc('examination_date')->first()?->examination_date,
            ];
        })->toArray();
    }

    /**
     * Merge duplicate patient records.
     * Useful when the same person has multiple records.
     */
    public function mergePatientRecords(Patient $primary, Patient $secondary): Patient
    {
        return DB::transaction(function () use ($primary, $secondary) {
            // Move all examinations to primary
            $secondary->examinations()->update(['patient_id' => $primary->id]);
            
            // Move all appointments to primary
            Appointment::where('clinic_patient_id', $secondary->id)
                ->update(['clinic_patient_id' => $primary->id]);
            
            // Merge data (keep primary's data, fill gaps from secondary)
            $fieldsToMerge = [
                'phone', 'email', 'address', 'date_of_birth', 'gender',
                'blood_type', 'allergies', 'chronic_diseases', 'medical_history',
                'family_history', 'emergency_contact_name', 'emergency_contact_phone'
            ];
            
            foreach ($fieldsToMerge as $field) {
                if (empty($primary->$field) && !empty($secondary->$field)) {
                    $primary->$field = $secondary->$field;
                }
            }
            
            // Link user if secondary has one and primary doesn't
            if (!$primary->user_id && $secondary->user_id) {
                $primary->user_id = $secondary->user_id;
            }
            
            $primary->save();
            
            // Soft delete secondary
            $secondary->delete();
            
            return $primary;
        });
    }

    /**
     * Normalize phone number for comparison.
     */
    protected function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }
        
        // Remove all non-numeric characters except +
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    /**
     * Search for potential duplicate patients by phone number only.
     */
    public function findPotentialDuplicates(Clinic $clinic, array $data): array
    {
        $duplicates = [];
        
        // Search by exact phone match only
        if (!empty($data['phone'])) {
            $normalizedPhone = $this->normalizePhone($data['phone']);
            $phoneMatch = Patient::forClinic($clinic->id)
                ->where('phone', $normalizedPhone)
                ->first();
            
            if ($phoneMatch) {
                $duplicates[] = [
                    'patient' => $phoneMatch,
                    'match_type' => 'phone',
                    'confidence' => 100,
                ];
            }
        }
        
        return $duplicates;
    }

}
