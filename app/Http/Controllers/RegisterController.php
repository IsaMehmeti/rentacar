<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use App\Models\Register;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class RegisterController extends Controller
{
    protected $pdfService;
    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $registers = Register::all();
        return view('register.index', compact('registers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create($client_id = null)
    {
        $clients = Client::all();
        $cars = Car::all();
        return view('register.create', compact('clients', 'cars', 'client_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {

        $request->validate([
        'car_id' => 'required|exists:cars,id',
        'client_id' => 'required|exists:clients,id',
        ]);
        $request->start_date = \Carbon\Carbon::create($request->start_date)
                ->addMinutes(\Carbon\Carbon::now('Europe/Belgrade')->minute)
                ->addHours(\Carbon\Carbon::now('Europe/Belgrade')->hour)
                ->isoFormat('YYYY-MM-DD HH:mm');

        $request->end_date = \Carbon\Carbon::create($request->end_date)
                        ->addHours(\Carbon\Carbon::now('Europe/Belgrade')->hour)
                        ->addMinutes(\Carbon\Carbon::now('Europe/Belgrade')->minute)
                        ->isoFormat('YYYY-MM-DD HH:mm');

        $register = Register::create($request->all());
        $register->start_date = $request->start_date;
        $register->end_date = $request->end_date;
        $register->save();
        $filename =  $this->pdfService->makePdf([
            'name' => $register->client->full_name,
            'phone' => $register->client->phone,
            'address' => $register->client->address,
            'driver' => $request->driver ?? $register->client->full_name,
            'id_card' => $register->client->id_card,
            'passaport' => $register->client->passaport_nr,
            'birth' => $register->client->birth,

            'target' => $register->car->target,
            'car_model' => $register->car->model.' '.$register->car->marsh.' - '.$register->car->color,
            'filename' => str_replace(' ', '', $register->car->model).''.str_replace(' ', '',$register->client->full_name),
            'production_year' => $register->car->production_year,
            'shasi_nr' => $register->car->shasi_nr,
            'start_date' => $register->start_date,
            'end_date' => $register->end_date,
            'derivat' => $register->fuel_status,
        ]);

        return response()->download(storage_path("files\$filename"), $filename, ['Content-Type' => 'application/jpg'])->deleteFileAfterSend(true);
    }

    /**
     * Display the specified resource.
     *
     * @param Register $register
     * @return Response
     */
    public function show(Register $register)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Register $register
     */
    public function edit(Register $register)
    {
        $clients = Client::all();
        $cars = Car::all();
        return view('register.edit', compact('register', 'clients', 'cars'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Register $register
     * @return RedirectResponse
     */
    public function update(Request $request, Register $register)
    {
        $request->validate([
        'car_id' => 'required|exists:cars,id',
        'client_id' => 'required|exists:clients,id',
        ]);
        $register->update($request->all());
        $register->save();
        return redirect()->back()->with('status', 'U ndryshua me sukses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Register $register
     * @return RedirectResponse
     */
    public function destroy(Register $register)
    {
        $register->delete();
        return redirect()->back()->with('danger', 'U fshi me sukses');
    }

    public function download($id)
    {
        $register = Register::findOrFail($id);
        $filename =  $this->pdfService->makePdf([
            'name' => $register->client->full_name,
            'phone' => $register->client->phone,
            'address' => $register->client->address,
            'driver' => $register->client->full_name,
            'id_card' => $register->client->id_card,
            'passaport' => $register->client->passaport_nr,
            'birth' => $register->client->birth,

            'target' => $register->car->target,
            'car_model' => $register->car->model.' '.$register->car->marsh.' - '.$register->car->color,
            'filename' => str_replace(' ', '', $register->car->model).''.str_replace(' ', '',$register->client->full_name),
            'production_year' => $register->car->production_year,
            'shasi_nr' => $register->car->shasi_nr,
            'start_date' => $register->start_date,
            'end_date' => $register->end_date,
            'derivat' => $register->fuel_status,
        ]);
        return response()->download(storage_path("files\$filename"), $filename, ['Content-Type' => 'application/jpg'])->deleteFileAfterSend(true);
    }

}
