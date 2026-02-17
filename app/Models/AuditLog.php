<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'action', 'auditable_type', 'auditable_id',
        'clinic_id', 'old_values', 'new_values',
        'ip_address', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Log an action.
     */
    public static function log(
        string $action,
        Model $model,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $clinicId = null
    ): self {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'clinic_id' => $clinicId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'viewed' => __('translation.audit.viewed'),
            'created' => __('translation.audit.created'),
            'updated' => __('translation.audit.updated'),
            'deleted' => __('translation.audit.deleted'),
            default => $this->action,
        };
    }

    public function getModelLabelAttribute(): string
    {
        $map = [
            Patient::class => __('translation.audit.models.patient'),
            Examination::class => __('translation.audit.models.examination'),
            Appointment::class => __('translation.audit.models.appointment'),
            LabTestResult::class => __('translation.audit.models.lab_test'),
            VaccinationRecord::class => __('translation.audit.models.vaccination'),
            PatientChronicDisease::class => __('translation.audit.models.chronic_disease'),
        ];

        return $map[$this->auditable_type] ?? class_basename($this->auditable_type);
    }

    public function scopeForClinic($query, int $clinicId)
    {
        return $query->where('clinic_id', $clinicId);
    }

    public function scopeForModel($query, string $type, int $id)
    {
        return $query->where('auditable_type', $type)->where('auditable_id', $id);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
