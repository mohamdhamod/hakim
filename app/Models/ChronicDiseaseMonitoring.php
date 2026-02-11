<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChronicDiseaseMonitoring extends Model
{
    use HasFactory;

    protected $table = 'chronic_disease_monitoring';

    protected $fillable = [
        'patient_chronic_disease_id',
        'examination_id',
        'recorded_by_user_id',
        'monitoring_date',
        'parameter_name',
        'parameter_value',
        'parameter_unit',
        'status',
        'notes'
    ];

    protected $casts = [
        'monitoring_date' => 'date',
    ];

    /**
     * Get the patient chronic disease.
     */
    public function patientChronicDisease()
    {
        return $this->belongsTo(PatientChronicDisease::class);
    }

    /**
     * Get the examination associated with this monitoring.
     */
    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * Get the healthcare provider who recorded this.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    /**
     * Scope: Uncontrolled status.
     */
    public function scopeUncontrolled($query)
    {
        return $query->whereIn('status', ['uncontrolled', 'critical']);
    }

    /**
     * Scope: Critical status.
     */
    public function scopeCritical($query)
    {
        return $query->where('status', 'critical');
    }

    /**
     * Scope: Filter by parameter.
     */
    public function scopeByParameter($query, $parameterName)
    {
        return $query->where('parameter_name', $parameterName);
    }
}
