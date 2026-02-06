<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class LabTestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'category',
        'unit',
        'normal_range_min',
        'normal_range_max',
        'normal_range_text',
        'order',
        'is_active'
    ];

    protected $casts = [
        'normal_range_min' => 'decimal:2',
        'normal_range_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the test results for this test type.
     */
    public function testResults()
    {
        return $this->hasMany(LabTestResult::class);
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
        return $query->orderBy('order')->orderBy('name_en');
    }

    /**
     * Scope: Filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
