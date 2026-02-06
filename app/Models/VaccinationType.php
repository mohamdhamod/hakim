<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'disease_prevented',
        'recommended_age_months',
        'age_group',
        'doses_required',
        'interval_days',
        'booster_after_months',
        'is_mandatory',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the vaccination records for this type.
     */
    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class);
    }

    /**
     * Get the localized name.
     */
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get the localized description.
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Scope: Active vaccination types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Mandatory vaccinations.
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Scope: Order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('recommended_age_months');
    }
}
