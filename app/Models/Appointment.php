<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'clinic_patient_id',
        'patient_name',
        'patient_phone',
        'patient_email',
        'appointment_date',
        'appointment_time',
        'reason',
        'notes',
        'status',
        'is_new_patient',
        'cancellation_reason',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'linked_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'linked_at' => 'datetime',
        'is_new_patient' => 'boolean',
    ];

    /**
     * Get the clinic for this appointment.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the patient (user account) for this appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the clinic's patient record for this appointment.
     */
    public function clinicPatient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'clinic_patient_id');
    }

    /**
     * Check if appointment is linked to a patient record.
     */
    public function isLinkedToPatient(): bool
    {
        return $this->clinic_patient_id !== null;
    }

    /**
     * Check if this is a guest booking (no user account).
     */
    public function isGuestBooking(): bool
    {
        return $this->patient_id === null;
    }

    /**
     * Get patient display name (from user or guest info).
     */
    public function getPatientDisplayNameAttribute(): string
    {
        if ($this->patient) {
            return $this->patient->name;
        }
        return $this->patient_name ?? __('translation.common.unknown');
    }

    /**
     * Check if appointment is upcoming.
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->appointment_date->isToday() || $this->appointment_date->isFuture();
    }

    /**
     * Check if appointment is today.
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->appointment_date->isToday();
    }

    /**
     * Scope for pending appointments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed appointments.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for today's appointments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    /**
     * Scope for upcoming appointments.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
                     ->whereIn('status', ['pending', 'confirmed']);
    }

    /**
     * Scope ordered by date and time.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('appointment_date')
                     ->orderBy('appointment_time');
    }

    /**
     * Confirm the appointment.
     */
    public function confirm(): bool
    {
        $this->status = 'confirmed';
        $this->confirmed_at = now();
        return $this->save();
    }

    /**
     * Complete the appointment.
     */
    public function complete(): bool
    {
        $this->status = 'completed';
        $this->completed_at = now();
        return $this->save();
    }

    /**
     * Cancel the appointment.
     */
    public function cancel(?string $reason = null): bool
    {
        $this->status = 'cancelled';
        $this->cancellation_reason = $reason;
        $this->cancelled_at = now();
        return $this->save();
    }
}
