<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class PdfService
{
    /**
     * @deprecated
     *
     */
    public function makePdf($data)
    {
        $img = Image::make(public_path('kontrata.jpg'));

        //Qeramarresi
        $img->text($data['name'], 115, 195, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // Tel:
        $img->text($data['phone'], 115, 220, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // Adresa
        $img->text($data['address'], 115, 246, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // Vozitesi
        $img->text($data['driver'], 115, 273, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // leternjo
        $img->text($data['id_card'], 115, 299, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // pasaport
        $img->text($data['passaport'], 115, 325, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // data lindjes
        $img->text($data['birth'], 115, 350, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // targeti
        $img->text($data['target'], 348, 195, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // modeli i vetures
        $img->text($data['car_model'], 348, 220, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(12);
        });
        // viti i prodhimit
        $img->text($data['production_year'], 348, 246, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // nr shasise
        $img->text($data['shasi_nr'], 348, 273, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // data e nisjes
        $img->text($data['start_date'], 348, 299, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // data e kthimit
        $img->text($data['end_date'], 348, 325, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        // gjendja e derivatit
        $img->text($data['derivat'], 348, 350, function ($font) {
            $font->file(public_path('Poppins-Light.ttf'));
            $font->size(15);
        });
        $filename = $data['filename'].time().'.jpg';
        $img->save(storage_path("/files/$filename"));
        return $filename;
    }

    public function generatePdf($register)
    {

        $start_date = Carbon::parse($register->start_date)->timezone('Europe/Belgrade');
        $end_date = Carbon::parse($register->end_date)->timezone('Europe/Belgrade');

        // get hour and minute
        $createdHour = $register->created_time['hour'];
        $createdMinute = $register->created_time['minute'];

        // set hour and minute to created at
        $start_date->setTime($createdHour, $createdMinute);
        $end_date->setTime($createdHour, $createdMinute);
        $data = [
            'full_name' => $register->client->full_name ?? '',
            'id_card' => $register->client->id_card ?? '',
            'address' => $register->client->address ?? '',
            'birth' => $register->client->birth ?? '',
            'birthplace' => $register->client->birthplace ?? '',
            'drivers_license_id' => $register->client->drivers_license_id ?? '',
            'phone' => $register->client->phone ?? '',

            'model' => $register->car->model ?? '',
            'shasi_nr' => $register->car->shasi_nr ?? '',
            'color' => $register->car->color ?? '',
            'target' => $register->car->target ?? '',
            'production_year' => $register->car->production_year ?? '',

            'fuel_status' => $register->fuel_status ?? '',

            'start_date' => $start_date->format('d-m-Y H:i') ?? '',
            'end_date' => $end_date->format('d-m-Y H:i') ?? '',

            'days' => $register->days ?? '',
            'price_per_day' => $register->price_per_day ?? '',
            'total_price' => $register->total_price ?? '',
        ];

        $pdf = Pdf::loadView('register-invoice', $data);


        return $pdf->stream('document.pdf');
    }
}
