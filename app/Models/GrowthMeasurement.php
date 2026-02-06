<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'examination_id',
        'measured_by_user_id',
        'measurement_date',
        'age_months',
        'weight_kg',
        'height_cm',
        'head_circumference_cm',
        'bmi',
        'weight_percentile',
        'height_percentile',
        'head_circumference_percentile',
        'bmi_percentile',
        'interpretation',
        'notes'
    ];

    protected $casts = [
        'measurement_date' => 'date',
        'weight_kg' => 'decimal:2',
        'height_cm' => 'decimal:2',
        'head_circumference_cm' => 'decimal:2',
        'bmi' => 'decimal:2',
        'weight_percentile' => 'decimal:2',
        'height_percentile' => 'decimal:2',
        'head_circumference_percentile' => 'decimal:2',
        'bmi_percentile' => 'decimal:2',
    ];

    /**
     * Get the patient that owns this measurement.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the examination associated with this measurement.
     */
    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * Get the healthcare provider who took the measurement.
     */
    public function measuredBy()
    {
        return $this->belongsTo(User::class, 'measured_by_user_id');
    }

    /**
     * Calculate BMI if weight and height are present.
     */
    public function calculateBmi()
    {
        if ($this->weight_kg && $this->height_cm) {
            $heightInMeters = $this->height_cm / 100;
            $this->bmi = $this->weight_kg / ($heightInMeters * $heightInMeters);
        }
    }

    /**
     * Scope: Filter by patient.
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Scope: Order by measurement date.
     */
    public function scopeChronological($query)
    {
        return $query->orderBy('measurement_date');
    }

    /**
     * Scope: Recent measurements.
     */
    public function scopeRecent($query, $months = 12)
    {
        return $query->where('measurement_date', '>=', now()->subMonths($months));
    }
}
