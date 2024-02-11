<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'model', 'marsh', 'production_year', 'target', 'shasi_nr', 'owner', 'color', 'comment'
    ];
    protected $with = ['images'];

    public function images()
    {
        return $this->hasMany( CarImage::class);
    }
}
