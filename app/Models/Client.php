<?php

namespace App\Models;

use App\Services\CarService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name', 'address', 'phone', 'id_card', 'id_expire', 'birth', 'birthplace', 'drivers_license_id'
    ];


    public function registers()
    {
        return $this->hasMany(Register::class);
    }

    // Accessor for the registration attribute
    public function getBirthAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format(CarService::$dateFormat);
        }
        return null;
    }
}
