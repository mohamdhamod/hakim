<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'vaccination_type_id',
        'administered_by_user_id',
        'vaccination_date',
        'dose_number',
        'batch_number',
        'manufacturer',
        'expiry_date',
        'site',
        'reaction_notes',
        'next_dose_due_date',
        'status'
    ];

    protected $casts = [
        'vaccination_date' => 'date',
        'expiry_date' => 'date',
        'next_dose_due_date' => 'date',
    ];

    /**
     * Get the patient that owns this vaccination record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the vaccination type.
     */
    public function vaccinationType()
    {
        return $this->belongsTo(VaccinationType::class);
    }

    /**
     * Get the healthcare provider who administered the vaccine.
     */
    public function administeredBy()
    {
        return $this->belongsTo(User::class, 'administered_by_user_id');
    }

    /**
     * Check if next dose is due soon.
     */
    public function isDueSoon($days = 30)
    {
        if (!$this->next_dose_due_date) {
            return false;
        }
        
        return $this->next_dose_due_date->diffInDays(now(), false) <= $days;
    }

    /**
     * Check if vaccination is overdue.
     */
    public function isOverdue()
    {
        if (!$this->next_dose_due_date) {
            return false;
        }
        
        return $this->next_dose_due_date->isPast();
    }

    /**
     * Scope: Completed vaccinations.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Scheduled vaccinations.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope: Overdue vaccinations.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'scheduled')
                     ->whereNotNull('next_dose_due_date')
                     ->where('next_dose_due_date', '<', now());
    }

    /**
     * Scope: Due soon.
     */
    public function scopeDueSoon($query, $days = 30)
    {
        return $query->where('status', 'scheduled')
                     ->whereNotNull('next_dose_due_date')
                     ->whereBetween('next_dose_due_date', [now(), now()->addDays($days)]);
    }
}
