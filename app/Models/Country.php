<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Storage;
/**
 * @mixin Builder
 * @property string name
 * @property string phone_extension
 * @property boolean is_active
 */

class Country extends Model implements TranslatableContract
{
    use Translatable;

    public $guarded = ['id'];

    public $translatedAttributes = ['name'];
    
    protected $appends = ['flag_url'];
    
    public function users(){
        return $this->hasMany(User::class,'country_id');
    }

    /**
     * Scope to order countries with priority countries first
     */
    public function scopeOrderedWithPriority($query)
    {
        $priorityCodes = ['US', 'GB', 'DE', 'FR', 'ES', 'SA'];
        
        return $query->orderByRaw("
            CASE 
                WHEN code = 'US' THEN 1
                WHEN code = 'GB' THEN 2
                WHEN code = 'DE' THEN 3
                WHEN code = 'FR' THEN 4
                WHEN code = 'ES' THEN 5
                WHEN code = 'SA' THEN 6
                ELSE 7
            END
        ")->orderBy('id');
    }

    /**
     * Get a public URL to the flag image.
     * Uses local flag images from public/images/flags/1x1/
     */
    public function getFlagUrlAttribute(){
        // If code is null or empty, return default flag
        if (empty($this->code)) {
            return asset('images/flags/1x1/un.svg');
        }
        
        // Use local flag images
        $code = strtolower($this->code);
        return asset("images/flags/1x1/{$code}.svg");
    }
}
