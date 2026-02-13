<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'specialty_id',
        'name',
        'address',
        'phone',
        'description',
        'logo',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected $appends = ['logo_path', 'status_label', 'status_badge_class', 'display_name'];

    /**
     * Get formatted clinic display name.
     * Returns "عيادة الدكتور [owner name]"
     */
    public function getDisplayNameAttribute()
    {
        $doctorName = $this->doctor?->name ?? $this->name;
        return __('translation.clinic.display_name_format', ['name' => $doctorName]);
    }

    /**
     * Get the specialty of the clinic.
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the doctor (user) who owns the clinic.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias for doctor relationship for backward compatibility.
     */
    public function user()
    {
        return $this->doctor();
    }

    /**
     * Get the admin who approved the clinic.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the patients of this clinic.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get the examinations of this clinic.
     */
    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    /**
     * Get the appointments of this clinic.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the services enabled for this clinic.
     */
    public function services()
    {
        return $this->belongsToMany(ClinicService::class, 'clinic_clinic_service')
            ->withTimestamps();
    }

    /**
     * Check if clinic has a specific service enabled.
     */
    public function hasService(string $serviceKey): bool
    {
        return $this->services()->where('key', $serviceKey)->exists();
    }

    /**
     * Get logo full path.
     */
    public function getLogoPathAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/img.png');
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => __('translation.clinic.status.pending'),
            'approved' => __('translation.clinic.status.approved'),
            'rejected' => __('translation.clinic.status.rejected'),
            default => $this->status,
        };
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Scope for pending clinics.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved clinics.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Check if clinic is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if clinic is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if clinic is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the clinic.
     */
    public function approve(int $approvedBy): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Reject the clinic.
     */
    public function reject(string $reason): bool
    {
        return $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_at' => null,
            'approved_by' => null,
        ]);
    }
}
