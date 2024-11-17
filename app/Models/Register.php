<?php

namespace App\Models;

use App\Services\CarService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder;

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
        'driver',
        'birthplace',
        'drivers_license_id',
        'days',
    ];
    protected $with = ['car', 'client'];

    protected $appends = ['status', 'created_time'];


    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Accessor for start_date attribute
    public function getStartDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format(CarService::$dateFormat);
        }
        return null;
    }

    // Accessor for the end_date attribute
    public function getEndDateAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format(CarService::$dateFormat);
        }
        return null;
    }


    // Accessor for the created time attribute
    public function getCreatedTimeAttribute()
    {
        $created_at = Carbon::parse($this->created_at)->timezone('Europe/Belgrade');
        if (!$created_at) {
            return null;
        }
        return [
            'hour' => $created_at->hour,
            'minute' => $created_at->minute,
            'time' => $created_at->format('H:i'),

        ];
    }


    // Appends for status attribute
    public function getStatusAttribute()
    {

        if (!$this->start_date || !$this->end_date) {
            return null;
        }
        $today = Carbon::today();
        $start = Carbon::parse($this->getRawOriginal('start_date'));
        $end = Carbon::parse($this->getRawOriginal('end_date'));

        if ($today->lt($start)) {
            return "pending";
        } elseif ($today->between($start, $end)) {
            return "in_progress";
        } else {
            return "completed";
        }
    }
}
