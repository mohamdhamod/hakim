<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'patient_id',
        'user_id',
        'clinic_id',
        'examination_number',
        'examination_date',
        'chief_complaint',
        'present_illness_history',
        'temperature',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'pulse_rate',
        'respiratory_rate',
        'weight',
        'height',
        'oxygen_saturation',
        'physical_examination',
        'diagnosis',
        'icd_code',
        'treatment_plan',
        'prescriptions',
        'lab_tests_ordered',
        'lab_tests_results',
        'imaging_ordered',
        'imaging_results',
        'follow_up_date',
        'follow_up_notes',
        'doctor_notes',
    ];

    protected $casts = [
        'examination_date' => 'datetime',
        'follow_up_date' => 'date',
        'temperature' => 'decimal:1',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    protected $appends = ['bmi', 'blood_pressure'];

    /**
     * Get the patient.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the clinic.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get examination attachments.
     */
    public function attachments()
    {
        return $this->hasMany(ExaminationAttachment::class);
    }

    /**
     * Calculate BMI.
     */
    public function getBmiAttribute()
    {
        if (!$this->weight || !$this->height) {
            return null;
        }
        $heightInMeters = $this->height / 100;
        return round($this->weight / ($heightInMeters * $heightInMeters), 1);
    }

    /**
     * Get blood pressure reading.
     */
    public function getBloodPressureAttribute()
    {
        if (!$this->blood_pressure_systolic || !$this->blood_pressure_diastolic) {
            return null;
        }
        return $this->blood_pressure_systolic . '/' . $this->blood_pressure_diastolic;
    }

    /**
     * Generate unique examination number based on patient file number.
     * Format: {file_number}-{counter} e.g. D5-46068-1-1, D5-46068-1-2
     */
    public static function generateExaminationNumber(int $patientId): string
    {
        $patient = Patient::findOrFail($patientId);
        $fileNumber = $patient->file_number;

        $lastExamination = self::where('patient_id', $patientId)
            ->where('examination_number', 'like', "{$fileNumber}-%")
            ->orderByRaw("CAST(SUBSTRING_INDEX(examination_number, '-', -1) AS UNSIGNED) DESC")
            ->first();

        if ($lastExamination) {
            $lastCounter = (int) collect(explode('-', $lastExamination->examination_number))->last();
            $newCounter = $lastCounter + 1;
        } else {
            $newCounter = 1;
        }

        return $fileNumber . '-' . $newCounter;
    }

    /**
     * Scope for today's examinations.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('examination_date', today());
    }
}
