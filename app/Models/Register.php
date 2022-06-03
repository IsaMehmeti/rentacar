<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'boolean'
    ];
    protected $fillable = [
        'client_id',
        'car_id',
        'price_per_day',
        'total_price',
        'start_date',
        'end_date',
        'comment',
        'fuel_status',
        'driver'
    ];
    protected $with = ['car', 'client'];


    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
