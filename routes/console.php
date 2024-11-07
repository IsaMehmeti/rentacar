<?php

use App\Models\Car;
use App\Services\CarService;
use App\Services\TwilioService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::call(function () {
    $cars = Car::all();
    $messages = [];
    foreach ($cars as $car) {
        $owner = $car->owner ?? false;
        $registrationInfo = CarService::createDatesToCompare($car->getRawOriginal('registration'));
        $technicalControlInfo = CarService::createDatesToCompare($car->getRawOriginal('technical_control'),
            $owner);
        $today = \Carbon\Carbon::today();

        if ($registrationInfo["controlDate"]->isSameDay($today)) {
            $messages[] = CarService::createMessage($car, $registrationInfo, 'Regjistrimi');
        }

        if ($technicalControlInfo["controlDate"]->isSameDay($today)) {
            $messages[] = CarService::createMessage($car, $technicalControlInfo, 'Kontrollimi Teknik');
        }

    }

    $mergedMessages = "";
    if (count($messages) === 1) {
        // If there's only one message, add it without numbering
        $mergedMessages .= $messages[0];
    } else {
        // If there are multiple messages, number each one
        foreach ($messages as $index => $message) {
            $mergedMessages .= ($index + 1).". ".$message."\n\n";
        }
    }
    $twilio = new TwilioService();
    $twilio->sendMessage(env("TWILIO_PHONE_TO") ?? "38348231702", $mergedMessages);
    Log::info("Message Sent.", [$mergedMessages]);
})->dailyAt('11:00')->timezone('Europe/Belgrade');
