<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            AuditLog::log('created', $model, null, $model->getAttributes(), $model->clinic_id ?? null);
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            if (empty($dirty)) return;

            $old = array_intersect_key($model->getOriginal(), $dirty);
            AuditLog::log('updated', $model, $old, $dirty, $model->clinic_id ?? null);
        });

        static::deleted(function ($model) {
            AuditLog::log('deleted', $model, $model->getAttributes(), null, $model->clinic_id ?? null);
        });
    }

    /**
     * Log a view action manually.
     */
    public function logView(?int $clinicId = null): void
    {
        AuditLog::log('viewed', $this, null, null, $clinicId ?? $this->clinic_id ?? null);
    }

    /**
     * Get audit logs for this model.
     */
    public function auditLogs()
    {
        return AuditLog::where('auditable_type', get_class($this))
            ->where('auditable_id', $this->getKey())
            ->orderByDesc('created_at');
    }
}
