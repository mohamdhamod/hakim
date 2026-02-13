<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'user_id',
        'is_active',
        'invited_at',
        'accepted_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'invited_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Get the clinic.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the user (editor).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active clinic users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
