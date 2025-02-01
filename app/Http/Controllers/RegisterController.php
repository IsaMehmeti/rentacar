<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use App\Models\Register;
use App\Services\PdfService;
use App\Services\WordService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Barryvdh\DomPDF\Facade\Pdf;


class RegisterController extends Controller
{
    protected $pdfService;
    protected $wordService;

    public function __construct(PdfService $pdfService, WordService $wordService)
    {
        $this->pdfService = $pdfService;
        $this->wordService = $wordService;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $registers = Register::with([
            'car' => function ($query) {
                $query->withTrashed();
            }, 'client' => function ($query) {
                $query->withTrashed();
            }
        ])->latest();

        return response()->json(
            $request->input('take')
                ? $registers->take($request->input('take'))->get()
                : $registers->get(),
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     */
    public function storeV1(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'client_id' => 'required|exists:clients,id',
        ]);
        $register = Register::create($request->all());
        $filename = $this->pdfService->makePdf([
            'name' => $register->client->full_name,
            'phone' => $register->client->phone,
            'address' => $register->client->address,
            'driver' => $request->driver ?? $register->client->full_name,
            'id_card' => $register->client->id_card,
            'passaport' => $register->client->passaport_nr,
            'birth' => $register->client->birth,

            'target' => $register->car->target,
            'car_model' => $register->car->model.' '.$register->car->marsh.' - '.$register->car->color,
            'filename' => str_replace(' ', '', $register->car->model).''.str_replace(' ', '',
                    $register->client->full_name),
            'production_year' => $register->car->production_year,
            'shasi_nr' => $register->car->shasi_nr,
            'start_date' => $register->start_date,
            'end_date' => $register->end_date,
            'derivat' => $register->fuel_status,
        ]);
        return response()->download(storage_path("/files/$filename"), $filename,
            ['Content-Type' => 'application/jpg'])->deleteFileAfterSend(true);
    }

    public function storev2(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'client_id' => 'required|exists:clients,id',
        ]);
        $timezone = new \DateTimeZone('Europe/Berlin');

        $start_date = new \DateTime($request->start_date, $timezone);
        $end_date = new \DateTime($request->end_date, $timezone);
        $currentDateTime = new \DateTime('now', $timezone);

        $start_date->setTime($currentDateTime->format('H'), $currentDateTime->format('i'));
        $end_date->setTime($currentDateTime->format('H'), $currentDateTime->format('i'));

        $start_date = date_format($start_date, "d-m-Y H:i");
        $end_date = date_format($end_date, "d-m-Y H:i");
        $request->start_date = $start_date;
        $request->end_date = $end_date;
        $register = Register::create($request->all());
        return $this->wordService->fillWordTemplate(
            public_path('kontrata.docx'),
            [
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

                'start_date' => $start_date ?? '',
                'end_date' => $end_date ?? '',

                'days' => $register->days ?? '',
                'price_per_day' => $register->price_per_day ?? '',
                'total_price' => $register->total_price ?? '',
            ]);
//        return response()->download(storage_path("/files/$filename"), $filename, ['Content-Type' => 'application/jpg'])->deleteFileAfterSend(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'price_per_day' => "sometimes",
            'total_price' => "sometimes",
            'comment' => "sometimes",
            'driver' => "sometimes",
            'birthplace' => "sometimes",
            'drivers_license_id' => "sometimes",
            'days' => "sometimes",
        ]);

        $start_date = Carbon::parse($request->start_date)->timezone('Europe/Belgrade');
        $end_date = Carbon::parse($request->end_date)->timezone('Europe/Belgrade');

        // Format the dates for database compatibility (Y-m-d)
        $validated['start_date'] = $start_date->format('Y-m-d');
        $validated['end_date'] = $end_date->format('Y-m-d');

        $client = Client::firstOrCreate(
            ['id' => $request->client['id'] ?? null],
            $request->client
        );
        $register = Register::create([
            "client_id" => $client->id,
            "car_id" => $request->car['id'],
            ...$validated
        ]);
        return $this->pdfService->generatePdf($register);

//        return response()->download(storage_path("/files/$filename"), $filename, ['Content-Type' => 'application/jpg'])->deleteFileAfterSend(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Register  $register
     */
    public function update(Request $request, Register $register)
    {
        $register->update([
            'comment' => $request->comment,
        ]);
        return response()->json([
            "status" => 'success',
            "data" => $register,
            "message" => "Kontrata u ndryshua me sukses"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Register  $register
     */
    public function destroy(Register $register)
    {
        $register->delete();
        return response()->json([
            "data" => null,
            "message" => "Kontrata u ndryshua me sukses"
        ]);
    }

    public function print($id)
    {
        $register = Register::where('id', $id)->with([
            'car' => function ($query) {
                $query->withTrashed();
            },
            'client' => function ($query) {
                $query->withTrashed();
            }
        ])->first();
        return $this->pdfService->generatePdf($register);
    }

}
