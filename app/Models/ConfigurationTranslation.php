<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigurationTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name',];
}
