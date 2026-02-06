<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'examination_id',
        'lab_test_type_id',
        'ordered_by_user_id',
        'test_date',
        'result_value',
        'result_text',
        'status',
        'lab_name',
        'lab_reference_number',
        'attachment_path',
        'interpretation',
        'doctor_notes'
    ];

    protected $casts = [
        'test_date' => 'date',
    ];

    /**
     * Get the patient that owns this test result.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the examination associated with this test.
     */
    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * Get the test type.
     */
    public function labTestType()
    {
        return $this->belongsTo(LabTestType::class);
    }

    /**
     * Get the doctor who ordered the test.
     */
    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'ordered_by_user_id');
    }

    /**
     * Check if result is abnormal.
     */
    public function isAbnormal()
    {
        return in_array($this->interpretation, ['abnormal_low', 'abnormal_high', 'critical']);
    }

    /**
     * Check if result is critical.
     */
    public function isCritical()
    {
        return $this->interpretation === 'critical';
    }

    /**
     * Scope: Completed tests only.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Pending tests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Abnormal results.
     */
    public function scopeAbnormal($query)
    {
        return $query->whereIn('interpretation', ['abnormal_low', 'abnormal_high', 'critical']);
    }

    /**
     * Scope: Filter by patient.
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
}
