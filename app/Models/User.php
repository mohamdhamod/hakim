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
     * Get the clinic associated with the doctor.
     */
    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'user_id');
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
}
