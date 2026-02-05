<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigTitle extends Model implements TranslatableContract
{
    use HasFactory, SoftDeletes, Translatable;
    public $translatedAttributes = ['title','description',];
    protected $fillable = [
    'key','page'
    ];
}
