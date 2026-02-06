<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChronicDiseaseTypeTranslation extends Model
{
    public $timestamps = true;
    
    protected $fillable = [
        'name',
        'description',
        'management_guidelines',
    ];
}
