<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProblem extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'clinic_id', 'recorded_by_user_id',
        'title', 'icd_code', 'onset_date', 'resolved_date',
        'status', 'severity', 'notes',
    ];

    protected $casts = [
        'onset_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeForClinic($query, int $clinicId)
    {
        return $query->where('clinic_id', $clinicId);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => __('translation.problem_list.status_active'),
            'resolved' => __('translation.problem_list.status_resolved'),
            'inactive' => __('translation.problem_list.status_inactive'),
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-danger',
            'resolved' => 'bg-success',
            'inactive' => 'bg-secondary',
            default => 'bg-light',
        };
    }

    public function getSeverityLabelAttribute(): string
    {
        return match ($this->severity) {
            'mild' => __('translation.problem_list.severity_mild'),
            'moderate' => __('translation.problem_list.severity_moderate'),
            'severe' => __('translation.problem_list.severity_severe'),
            default => '-',
        };
    }
}
