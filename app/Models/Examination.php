<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Examination extends Model
{
    use HasFactory, SoftDeletes;

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
        'status',
    ];

    protected $casts = [
        'examination_date' => 'datetime',
        'follow_up_date' => 'date',
        'temperature' => 'decimal:1',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    protected $appends = ['status_label', 'status_badge_class', 'bmi', 'blood_pressure'];

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
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'scheduled' => __('translation.examination.status.scheduled'),
            'in_progress' => __('translation.examination.status.in_progress'),
            'completed' => __('translation.examination.status.completed'),
            'cancelled' => __('translation.examination.status.cancelled'),
            default => $this->status,
        };
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'scheduled' => 'bg-info',
            'in_progress' => 'bg-warning',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
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
     * Generate unique examination number.
     */
    public static function generateExaminationNumber(int $clinicId): string
    {
        $year = date('Y');
        $month = date('m');
        $lastExamination = self::where('clinic_id', $clinicId)
            ->where('examination_number', 'like', "E{$year}{$month}%")
            ->orderBy('examination_number', 'desc')
            ->first();

        if ($lastExamination) {
            $lastNumber = (int) substr($lastExamination->examination_number, -5);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('E%s%s%05d', $year, $month, $newNumber);
    }

    /**
     * Scope for today's examinations.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('examination_date', today());
    }

    /**
     * Scope for upcoming examinations.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('examination_date', '>=', now())
            ->where('status', 'scheduled');
    }

    /**
     * Scope for completed examinations.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Check if examination is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Mark examination as completed.
     */
    public function markAsCompleted(): bool
    {
        return $this->update(['status' => 'completed']);
    }

    /**
     * Mark examination as in progress.
     */
    public function markAsInProgress(): bool
    {
        return $this->update(['status' => 'in_progress']);
    }
}
