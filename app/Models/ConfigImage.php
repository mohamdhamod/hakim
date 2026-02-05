<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigImage extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name','page','key'
    ];
    protected $appends = ['image_path'];
    public function getImagePathAttribute(){
        return $this->name != null ? asset('storage/'.$this->name) : asset('images/img.png');
    }
}
