<?php

namespace App\Models;

use App\Services\CarService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'model', 'marsh', 'production_year', 'target', 'shasi_nr', 'owner', 'color', 'comment',

        //
        'technical_control', 'registration'
    ];

    protected $appends = [
        'registration_status', 'technical_control_status'
    ];

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    public function getRegistrationStatusAttribute()
    {
        if (!$this->registration) {
            return null;
        }
        return CarService::getDateStatus($this->getRawOriginal('registration'));
    }

    public function getTechnicalControlStatusAttribute()
    {
        if (!$this->technical_control) {
            return null;
        }
        $owner = $this->owner ? $this->owner : false;
        return CarService::getDateStatus($this->getRawOriginal('technical_control'), $owner);
    }

    // Accessor for the technical_control attribute
    public function getTechnicalControlAttribute($value)
    {

        if ($value) {
            return Carbon::parse($value)->format(CarService::$dateFormat);
        }
        return null;
    }

    // Accessor for the registration attribute
    public function getRegistrationAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format(CarService::$dateFormat);
        }
        return null;
    }
}
