<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'file_number';
    }

    protected $fillable = [
        'user_id',
        'clinic_id',
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
     * Get the clinic this patient belongs to.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
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
            'male' => __('translation.patient.gender.male'),
            'female' => __('translation.patient.gender.female'),
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
     * Format: D5-00001 (First letter of doctor's email + clinic ID + sequence)
     */
    public static function generateFileNumber(int $clinicId): string
    {
        // Get clinic and doctor's email
        $clinic = \App\Models\Clinic::with('doctor')->find($clinicId);
        $doctorEmail = $clinic?->doctor?->email ?? 'X';
        $prefix = strtoupper(substr($doctorEmail, 0, 1));
        
        // Build the prefix pattern
        $pattern = "{$prefix}{$clinicId}-";
        
        // Get all patients with this pattern and find the highest number
        $patients = self::where('clinic_id', $clinicId)
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
     * Get latest examination.
     */
    public function latestExamination()
    {
        return $this->hasOne(Examination::class)->latest('examination_date');
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
}
