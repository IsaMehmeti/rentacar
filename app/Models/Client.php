<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

     protected $fillable = [
        'full_name', 'address', 'phone', 'id_card', 'id_expire', 'birth', 'passaport_nr'
    ];

    public function registers()
    {
        return $this->hasMany(Register::class);
     }
}
