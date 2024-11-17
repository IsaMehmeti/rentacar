<?php

namespace App\Services;


use Illuminate\Support\Carbon;

class CarService
{
    public static $dateFormat = 'd-m-Y';

    public static function getDateStatus($date, $owner = true)
    {
        $dateInfo = self::createDatesToCompare($date, $owner);


        $oneWeekBeforeControlDate = $dateInfo['controlDate']->copy()->subWeek();

        if ($dateInfo['controlDate']->isPast()) {
            return 'danger';
        } elseif (Carbon::now()->between($oneWeekBeforeControlDate, $dateInfo['controlDate'])) {
            return 'warn';
        } else {
            return 'success';
        }
    }

    public static function createDatesToCompare($date, $addYear = true): array
    {
        $registeredDate = Carbon::parse($date);
        // date that registration or TC expires
        // If there's an owner, add a year, otherwise add 6 months
        $controlDate = $addYear ? $registeredDate->copy()->addYear() : $registeredDate->copy()->addMonths(6);

        return [
            "registeredDate" => $registeredDate,
            "controlDate" => $controlDate
        ];
    }

    public static function createMessage($car, $info, $type)
    {
        return "{$type} për veturën {$car->model} {$car->marsh} {$car->color}: {$car->target} ka skaduar sot.\n{$type} i fundit i kryer në datën: {$car->technical_control["value"]}.";
    }

}
