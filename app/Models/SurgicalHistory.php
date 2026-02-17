<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurgicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'clinic_id', 'recorded_by_user_id',
        'procedure_name', 'procedure_date', 'hospital',
        'surgeon', 'indication', 'complications', 'notes',
    ];

    protected $casts = [
        'procedure_date' => 'date',
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

    public function scopeForClinic($query, int $clinicId)
    {
        return $query->where('clinic_id', $clinicId);
    }
}
