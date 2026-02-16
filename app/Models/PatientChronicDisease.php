<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientChronicDisease extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'chronic_disease_type_id',
        'diagnosed_by_user_id',
        'diagnosis_date',
        'severity',
        'status',
        'treatment_plan',
        'notes',
        'last_followup_date',
        'next_followup_date'
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
        'last_followup_date' => 'date',
        'next_followup_date' => 'date',
    ];

    /**
     * Get the clinic that owns this chronic disease record.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the patient that owns this chronic disease.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the chronic disease type.
     */
    public function chronicDiseaseType()
    {
        return $this->belongsTo(ChronicDiseaseType::class);
    }

    /**
     * Get the doctor who diagnosed.
     */
    public function diagnosedBy()
    {
        return $this->belongsTo(User::class, 'diagnosed_by_user_id');
    }

    /**
     * Get the monitoring records.
     */
    public function monitoringRecords()
    {
        return $this->hasMany(ChronicDiseaseMonitoring::class);
    }

    /**
     * Check if follow-up is due soon.
     */
    public function isFollowupDueSoon($days = 7)
    {
        if (!$this->next_followup_date) {
            return false;
        }
        
        return $this->next_followup_date->diffInDays(now(), false) <= $days;
    }

    /**
     * Check if follow-up is overdue.
     */
    public function isFollowupOverdue()
    {
        if (!$this->next_followup_date) {
            return false;
        }
        
        return $this->next_followup_date->isPast();
    }

    /**
     * Scope: Active diseases.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Overdue follow-ups.
     */
    public function scopeOverdueFollowup($query)
    {
        return $query->where('status', 'active')
                     ->whereNotNull('next_followup_date')
                     ->where('next_followup_date', '<', now());
    }

    /**
     * Scope: Due soon.
     */
    public function scopeFollowupDueSoon($query, $days = 7)
    {
        return $query->where('status', 'active')
                     ->whereNotNull('next_followup_date')
                     ->whereBetween('next_followup_date', [now(), now()->addDays($days)]);
    }
}
