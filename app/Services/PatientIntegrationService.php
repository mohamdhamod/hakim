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
 * Flow scenarios:
 * 1. Registered user books appointment → Link to existing or create new Patient record
 * 2. Guest books appointment → Create Patient record when appointment is confirmed
 * 3. Clinic creates patient directly → Patient record without user account
 * 4. User registers with existing patient data → Link to existing Patient records
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
     * Find existing patient by user account, email, or phone.
     */
    public function findExistingPatient(Appointment $appointment): ?Patient
    {
        $clinic = $appointment->clinic;
        
        // Priority 1: Find by linked user account
        if ($appointment->patient_id) {
            $patient = Patient::where('clinic_id', $clinic->id)
                ->where('user_id', $appointment->patient_id)
                ->first();
            
            if ($patient) {
                return $patient;
            }
        }
        
        // Priority 2: Find by email
        $email = $appointment->patient?->email ?? $appointment->patient_email;
        if ($email) {
            $patient = Patient::where('clinic_id', $clinic->id)
                ->where('email', $email)
                ->first();
            
            if ($patient) {
                // Link user if not already linked
                if ($appointment->patient_id && !$patient->user_id) {
                    $patient->update(['user_id' => $appointment->patient_id]);
                }
                return $patient;
            }
        }
        
        // Priority 3: Find by phone
        $phone = $appointment->patient?->phone ?? $appointment->patient_phone;
        if ($phone) {
            $patient = Patient::where('clinic_id', $clinic->id)
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
        
        return Patient::create([
            'user_id' => $appointment->patient_id,
            'clinic_id' => $clinic->id,
            'file_number' => $this->generateFileNumber($clinic),
            'full_name' => $user?->name ?? $appointment->patient_name,
            'phone' => $user?->phone ?? $appointment->patient_phone,
            'email' => $user?->email ?? $appointment->patient_email,
        ]);
    }

    /**
     * Link a user account to existing patient records across all clinics.
     * Called when a user registers or logs in.
     */
    public function linkUserToPatientRecords(User $user): int
    {
        $linkedCount = 0;
        
        // Find patient records with matching email or phone
        $patients = Patient::whereNull('user_id')
            ->where(function ($query) use ($user) {
                $query->where('email', $user->email);
                if ($user->phone) {
                    $query->orWhere('phone', $this->normalizePhone($user->phone));
                }
            })
            ->get();
        
        foreach ($patients as $patient) {
            $patient->update(['user_id' => $user->id]);
            $linkedCount++;
        }
        
        // Also update appointments
        if ($user->email) {
            Appointment::whereNull('patient_id')
                ->where('patient_email', $user->email)
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
     * Format: D5-00001 (First letter of doctor's email + clinic ID + sequence)
     */
    public function generateFileNumber(Clinic $clinic): string
    {
        // Get first letter of clinic doctor's email (uppercase)
        $doctorEmail = $clinic->doctor?->email ?? 'X';
        $prefix = strtoupper(substr($doctorEmail, 0, 1));
        
        // Clinic ID
        $clinicId = $clinic->id;
        
        // Build the prefix pattern
        $pattern = "{$prefix}{$clinicId}-";
        
        // Get all patients with this pattern and find the highest number
        $patients = Patient::where('clinic_id', $clinic->id)
            ->where('file_number', 'like', "{$pattern}%")
            ->pluck('file_number');
        
        $maxNumber = 0;
        foreach ($patients as $fileNumber) {
            if (preg_match('/-(\d+)$/', $fileNumber, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }
        
        $newNumber = str_pad($maxNumber + 1, 5, '0', STR_PAD_LEFT);
        $fileNumber = "{$pattern}{$newNumber}";
        
        return $fileNumber;
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
            ->with(['clinic', 'examinations'])
            ->get();
        
        return $patients->map(function ($patient) {
            return [
                'patient' => $patient,
                'clinic' => $patient->clinic,
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
     * Search for potential duplicate patients.
     * Only returns duplicates with high confidence (80%+) to avoid false positives.
     */
    public function findPotentialDuplicates(Clinic $clinic, array $data): array
    {
        $duplicates = [];
        $minConfidenceThreshold = 80; // Only show duplicates with 80%+ confidence
        
        // Search by exact phone match (most reliable)
        if (!empty($data['phone'])) {
            $normalizedPhone = $this->normalizePhone($data['phone']);
            $phoneMatch = Patient::where('clinic_id', $clinic->id)
                ->where('phone', $normalizedPhone)
                ->first();
            
            if ($phoneMatch) {
                $duplicates[$phoneMatch->id] = [
                    'patient' => $phoneMatch,
                    'match_type' => 'phone',
                    'confidence' => 95,
                ];
            }
        }
        
        // Search by exact email match (very reliable)
        if (!empty($data['email'])) {
            $emailMatch = Patient::where('clinic_id', $clinic->id)
                ->where('email', strtolower($data['email']))
                ->first();
            
            if ($emailMatch) {
                if (isset($duplicates[$emailMatch->id])) {
                    $duplicates[$emailMatch->id]['match_type'] .= '+email';
                    $duplicates[$emailMatch->id]['confidence'] = 100;
                } else {
                    $duplicates[$emailMatch->id] = [
                        'patient' => $emailMatch,
                        'match_type' => 'email',
                        'confidence' => 95,
                    ];
                }
            }
        }
        
        // Search by name similarity (only for very high matches)
        if (!empty($data['full_name'])) {
            // Get all patients and check similarity (only if not too many)
            $patients = Patient::where('clinic_id', $clinic->id)
                ->limit(500)
                ->get(['id', 'full_name', 'phone', 'email', 'file_number', 'date_of_birth', 'gender', 'blood_type', 'address', 'user_id', 'clinic_id', 'created_at', 'updated_at']);
            
            foreach ($patients as $patient) {
                $similarity = $this->calculateNameSimilarity($data['full_name'], $patient->full_name);
                
                // Only consider very high similarity (90%+) for name-only matches
                if ($similarity >= 90) {
                    if (isset($duplicates[$patient->id])) {
                        $duplicates[$patient->id]['match_type'] .= '+name';
                        $duplicates[$patient->id]['confidence'] = min(100, $duplicates[$patient->id]['confidence'] + 10);
                    } else {
                        $duplicates[$patient->id] = [
                            'patient' => $patient,
                            'match_type' => 'name',
                            'confidence' => $similarity,
                        ];
                    }
                }
            }
        }
        
        // Filter out low confidence matches
        $duplicates = array_filter($duplicates, fn($d) => $d['confidence'] >= $minConfidenceThreshold);
        
        // Sort by confidence
        uasort($duplicates, fn($a, $b) => $b['confidence'] <=> $a['confidence']);
        
        return array_values($duplicates);
    }

    /**
     * Calculate name similarity percentage.
     */
    protected function calculateNameSimilarity(string $name1, string $name2): int
    {
        similar_text(strtolower($name1), strtolower($name2), $percent);
        return (int) $percent;
    }
}
