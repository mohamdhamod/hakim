<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'latitude',
        'longitude',
        'term_and_policy',
        'google_id',
        'current_session_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(RoleEnum::ADMIN);
    }

    /**
     * Check if user is doctor
     */
    public function isDoctor(): bool
    {
        return $this->hasRole(RoleEnum::DOCTOR);
    }

    /**
     * Check if user is patient
     */
    public function isPatient(): bool
    {
        return $this->hasRole(RoleEnum::PATIENT);
    }

    /**
     * Check if user is clinic patient editor
     */
    public function isClinicPatientEditor(): bool
    {
        return $this->hasRole(RoleEnum::CLINIC_PATIENT_EDITOR);
    }

    /**
     * Get the clinic associated with the doctor.
     */
    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'user_id');
    }

    /**
     * Get the clinics where user is an editor.
     */
    public function editorClinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_users')
            ->withPivot(['is_active', 'invited_at', 'accepted_at'])
            ->withTimestamps();
    }

    /**
     * Get active clinic for editor.
     */
    public function getEditorClinicAttribute()
    {
        return $this->editorClinics()->wherePivot('is_active', true)->first();
    }

    /**
     * Get the working clinic (for doctor or editor).
     */
    public function getWorkingClinicAttribute()
    {
        if ($this->isDoctor()) {
            return $this->clinic;
        }
        if ($this->isClinicPatientEditor()) {
            return $this->editorClinic;
        }
        return null;
    }

    /**
     * Get the patient record if user is linked to a patient.
     */
    public function patientRecord()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Get appointments for this user (as patient).
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return null;
    }

    /**
     * Approve a doctor user (when their clinic is approved).
     * Currently a no-op since approval is managed via Clinic status.
     */
    public function approveDoctor(): bool
    {
        // Future: could set email_verified_at or other approval flags
        return true;
    }

    /**
     * Reject a doctor user (when their clinic is rejected).
     * Currently a no-op since rejection is managed via Clinic status.
     */
    public function rejectDoctor(?string $reason = null): bool
    {
        // Future: could send rejection email or set status flag
        return true;
    }
}
