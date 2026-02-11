<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class LabTestType extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    protected $fillable = [
        'key',
        'category',
        'unit',
        'normal_range_min',
        'normal_range_max',
        'normal_range_text',
        'age_gender_ranges',
        'order',
        'is_active'
    ];

    public array $translatedAttributes = [
        'name',
        'description',
    ];

    protected $casts = [
        'normal_range_min' => 'decimal:2',
        'normal_range_max' => 'decimal:2',
        'age_gender_ranges' => 'array',
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
     * Get the test results for this test type.
     */
    public function testResults()
    {
        return $this->hasMany(LabTestResult::class);
    }

    /**
     * Scope: Active test types only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope: Filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get normal range for specific age and gender.
     */
    public function getNormalRangeForPatient($birthDate, $gender)
    {
        // If no age/gender ranges, return default
        if (!$this->age_gender_ranges) {
            return [
                'min' => $this->normal_range_min,
                'max' => $this->normal_range_max,
                'text' => $this->normal_range_text,
            ];
        }

        $age = \Carbon\Carbon::parse($birthDate)->age;
        $rangeKey = $this->getAgeGenderRangeKey($age, $gender);
        
        if (isset($this->age_gender_ranges[$rangeKey])) {
            return $this->age_gender_ranges[$rangeKey];
        }

        // Fallback to default
        return [
            'min' => $this->normal_range_min,
            'max' => $this->normal_range_max,
            'text' => $this->normal_range_text,
        ];
    }

    /**
     * Get age/gender range key.
     */
    private function getAgeGenderRangeKey($age, $gender)
    {
        $genderPrefix = strtolower($gender); // 'male' or 'female'
        
        if ($age < 1) {
            return "{$genderPrefix}_infant_0_1";
        } elseif ($age < 5) {
            return "{$genderPrefix}_child_1_5";
        } elseif ($age < 12) {
            return "{$genderPrefix}_child_5_12";
        } elseif ($age < 18) {
            return "{$genderPrefix}_teen_12_18";
        } else {
            return "{$genderPrefix}_adult";
        }
    }

    /**
     * Check if value is abnormal for patient.
     */
    public function isAbnormalForPatient($value, $birthDate, $gender)
    {
        $range = $this->getNormalRangeForPatient($birthDate, $gender);
        
        if (!isset($range['min']) || !isset($range['max'])) {
            return false;
        }

        return $value < $range['min'] || $value > $range['max'];
    }
}
