<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class ChronicDiseaseType extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = [
        'key',
        'icd11_code',
        'category',
        'followup_interval_days',
        'is_active'
    ];

    public array $translatedAttributes = [
        'name',
        'description',
        'management_guidelines',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['name', 'description'];

    /**
     * Get the name attribute from translation.
     */
    public function getNameAttribute()
    {
        return $this->translate()?->name ?? $this->key;
    }

    /**
     * Get the description attribute from translation.
     */
    public function getDescriptionAttribute()
    {
        return $this->translate()?->description ?? '';
    }

    /**
     * Get the patient chronic diseases for this type.
     */
    public function patientChronicDiseases()
    {
        return $this->hasMany(PatientChronicDisease::class);
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
