<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClinicService extends Model implements TranslatableContract
{
    use Translatable;

    protected $fillable = [
        'key',
        'icon',
        'color',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public array $translatedAttributes = [
        'name',
        'description',
    ];

    /**
     * Get the clinics that have this service.
     */
    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class, 'clinic_clinic_service')
            ->withTimestamps();
    }

    /**
     * Scope to get only active services.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get service by key.
     */
    public static function findByKey(string $key)
    {
        return static::where('key', $key)->first();
    }
}
