<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class VaccinationType extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = [
        'key',
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

    public array $translatedAttributes = [
        'name',
        'description',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = ['name', 'description'];

    /**
     * Get translated name attribute for JSON serialization.
     */
    public function getNameAttribute()
    {
        return $this->translate()?->name ?? $this->key;
    }

    /**
     * Get translated description attribute for JSON serialization.
     */
    public function getDescriptionAttribute()
    {
        return $this->translate()?->description;
    }

    /**
     * Get the vaccination records for this type.
     */
    public function vaccinationRecords()
    {
        return $this->hasMany(VaccinationRecord::class);
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
