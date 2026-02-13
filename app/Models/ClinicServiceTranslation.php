<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicServiceTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];
}
