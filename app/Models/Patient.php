<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory, Auditable;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'file_number';
    }

    protected $fillable = [
        'user_id',
        'file_number',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'blood_type',
        'allergies',
        'chronic_diseases',
        'medical_history',
        'family_history',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'smoking_status',
        'alcohol_status',
        'occupation',
        'marital_status',
        'lifestyle_notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    protected $appends = ['age', 'gender_label', 'blood_type_label'];

    /**
     * Get the user account linked to this patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clinics this patient belongs to.
     */
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_patient')
            ->withTimestamps();
    }

    /**
     * Get the first/primary clinic (backward compatibility helper).
     */
    public function getClinicAttribute()
    {
        return $this->clinics->first();
    }

    /**
     * Check if patient belongs to a specific clinic.
     */
    public function belongsToClinic(int $clinicId): bool
    {
        return $this->clinics()->where('clinics.id', $clinicId)->exists();
    }

    /**
     * Get the examinations of this patient.
     */
    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    /**
     * Get appointments linked to this patient record.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'clinic_patient_id');
    }

    /**
     * Check if patient has an active user account.
     */
    public function hasUserAccount(): bool
    {
        return $this->user_id !== null;
    }

    /**
     * Check if this is a new patient (no completed examinations).
     */
    public function isNewPatient(): bool
    {
        return !$this->examinations()
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Get the total number of visits (completed examinations).
     */
    public function getTotalVisitsAttribute(): int
    {
        return $this->examinations()
            ->where('status', 'completed')
            ->count();
    }

    /**
     * Get patient's age.
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->age;
    }

    /**
     * Get gender label.
     */
    public function getGenderLabelAttribute()
    {
        return match($this->gender) {
            'male' => __('translation.patient.male'),
            'female' => __('translation.patient.female'),
            default => '-',
        };
    }

    /**
     * Get blood type label.
     */
    public function getBloodTypeLabelAttribute()
    {
        $types = [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
            'O+' => 'O+',
            'O-' => 'O-',
        ];
        return $types[$this->blood_type] ?? '-';
    }

    /**
     * Generate unique file number for patient.
     * Format: D5-46068-1 (Prefix + Excel date serial + daily sequence)
     * Excel date serial = days since 1899-12-30
     */
    public static function generateFileNumber(int $clinicId): string
    {
        // Get clinic and doctor's email
        $clinic = \App\Models\Clinic::with('doctor')->find($clinicId);
        $doctorEmail = $clinic?->doctor?->email ?? 'X';
        $prefix = strtoupper(substr($doctorEmail, 0, 1));
        
        // Excel date serial (days since 1899-12-30)
        $excelEpoch = \Carbon\Carbon::create(1899, 12, 30);
        $dateSerial = (int) $excelEpoch->diffInDays(now());
        
        // Build the pattern for today: D5-46068-
        $todayPattern = "{$prefix}{$clinicId}-{$dateSerial}-";
        
        // Find highest daily sequence for this clinic today
        $maxSeq = 0;
        $patients = self::where('file_number', 'like', "{$todayPattern}%")->pluck('file_number');
        
        foreach ($patients as $fileNumber) {
            if (preg_match('/-(\d+)$/', $fileNumber, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxSeq) {
                    $maxSeq = $num;
                }
            }
        }
        
        $fileNumber = "{$todayPattern}" . ($maxSeq + 1);
        
        return $fileNumber;
    }

    /**
     * Get latest examination.
     */
    public function latestExamination()
    {
        return $this->hasOne(Examination::class)->latest('examination_date');
    }

    /**
     * Get lab test results for this patient.
     */
    public function labTestResults()
    {
        return $this->hasMany(LabTestResult::class);
    }

    /**
     * Get vaccination records for this patient.
     */
    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class);
    }

    /**
     * Get growth measurements for this patient.
     */
    public function growthMeasurements()
    {
        return $this->hasMany(GrowthMeasurement::class);
    }

    /**
     * Get chronic diseases for this patient.
     */
    public function chronicDiseases()
    {
        return $this->hasMany(PatientChronicDisease::class);
    }

    public function surgicalHistories()
    {
        return $this->hasMany(SurgicalHistory::class);
    }

    public function problems()
    {
        return $this->hasMany(PatientProblem::class);
    }

    public function activeProblems()
    {
        return $this->hasMany(PatientProblem::class)->where('status', 'active');
    }

    /**
     * Scope for searching patients.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
              ->orWhere('file_number', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    /**
     * Scope to filter patients belonging to a specific clinic.
     */
    public function scopeForClinic($query, int $clinicId)
    {
        return $query->whereHas('clinics', fn($q) => $q->where('clinics.id', $clinicId));
    }
}
