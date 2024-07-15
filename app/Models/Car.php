<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'model', 'marsh', 'production_year', 'target', 'shasi_nr', 'owner', 'color', 'comment',

        //
        'technical_control', 'registration'
    ];

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }
}
