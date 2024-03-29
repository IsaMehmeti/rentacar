<?php

namespace App\Models;

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
}
