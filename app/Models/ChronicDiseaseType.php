<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChronicDiseaseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'icd11_code',
        'category',
        'management_guidelines_en',
        'management_guidelines_ar',
        'followup_interval_days',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the patient chronic diseases for this type.
     */
    public function patientChronicDiseases()
    {
        return $this->hasMany(PatientChronicDisease::class);
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
     * Get the localized management guidelines.
     */
    public function getManagementGuidelinesAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->management_guidelines_ar : $this->management_guidelines_en;
    }

    /**
     * Scope: Active disease types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
