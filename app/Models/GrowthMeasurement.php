<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
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

    protected $appends = ['weight', 'height', 'head_circumference'];

    /**
     * Get the clinic that owns this measurement.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the patient that owns this measurement.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get weight accessor for JSON serialization.
     */
    public function getWeightAttribute()
    {
        return $this->weight_kg;
    }

    /**
     * Get height accessor for JSON serialization.
     */
    public function getHeightAttribute()
    {
        return $this->height_cm;
    }

    /**
     * Get head circumference accessor for JSON serialization.
     */
    public function getHeadCircumferenceAttribute()
    {
        return $this->head_circumference_cm;
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
        if ($this->weight_kg && $this->height_cm && $this->height_cm > 0) {
            $heightInMeters = $this->height_cm / 100;
            $bmi = $this->weight_kg / ($heightInMeters * $heightInMeters);
            // Limit BMI to reasonable range (1-100) and round to 2 decimals
            $this->bmi = min(100, max(1, round($bmi, 2)));
        }
    }

    /**
     * Calculate WHO percentiles for all metrics.
     */
    public function calculatePercentiles()
    {
        if (!$this->patient || !$this->patient->gender || !$this->age_months) {
            return;
        }

        $calculator = app(\App\Services\WhoGrowthCalculator::class);
        $gender = $this->patient->gender;
        $ageMonths = $this->age_months;

        // Calculate weight percentile
        if ($this->weight_kg) {
            $this->weight_percentile = $calculator->calculateWeightPercentile(
                $ageMonths,
                $gender,
                $this->weight_kg
            );
        }

        // Calculate height percentile
        if ($this->height_cm) {
            $this->height_percentile = $calculator->calculateHeightPercentile(
                $ageMonths,
                $gender,
                $this->height_cm
            );
        }

        // Calculate BMI percentile
        if ($this->bmi) {
            $this->bmi_percentile = $calculator->calculateBmiPercentile(
                $ageMonths,
                $gender,
                $this->bmi
            );
        }

        // Calculate head circumference percentile
        if ($this->head_circumference_cm) {
            $this->head_circumference_percentile = $calculator->calculateHeadCircumferencePercentile(
                $ageMonths,
                $gender,
                $this->head_circumference_cm
            );
        }

        // Set interpretation
        $this->interpretation = $this->getOverallInterpretation($calculator);
    }

    /**
     * Get overall growth interpretation.
     * Enum values: underweight, normal, overweight, obese
     */
    private function getOverallInterpretation($calculator)
    {
        $interpretations = [];

        if ($this->weight_percentile !== null) {
            $interpretations[] = $calculator->getInterpretation($this->weight_percentile, 'weight');
        }

        if ($this->height_percentile !== null) {
            $interpretations[] = $calculator->getInterpretation($this->height_percentile, 'height');
        }

        if ($this->bmi_percentile !== null) {
            $interpretations[] = $calculator->getInterpretation($this->bmi_percentile, 'bmi');
        }

        // Return interpretation based on BMI percentile primarily
        if ($this->bmi_percentile !== null) {
            if ($this->bmi_percentile < 5) {
                return 'underweight';
            } elseif ($this->bmi_percentile >= 85 && $this->bmi_percentile < 95) {
                return 'overweight';
            } elseif ($this->bmi_percentile >= 95) {
                return 'obese';
            }
        }

        // Fallback to weight percentile
        if ($this->weight_percentile !== null) {
            if ($this->weight_percentile < 5) {
                return 'underweight';
            } elseif ($this->weight_percentile >= 95) {
                return 'overweight';
            }
        }

        return 'normal';
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
