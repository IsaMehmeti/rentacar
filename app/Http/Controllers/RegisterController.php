<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Client;
use App\Models\Register;
use App\Services\PdfService;
use App\Services\WordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
    public function index()
    {
        $registers = Register::with(['car' => function ($query) {
            $query->withTrashed();
        }, 'client' => function ($query) {
            $query->withTrashed();
        }])->get();
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
    public function storeV1(Request $request)
    {
        $request->validate([
        'car_id' => 'required|exists:cars,id',
        'client_id' => 'required|exists:clients,id',
        ]);
        $register = Register::create($request->all());
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
        return response()->download(storage_path("/files/$filename"), $filename, ['Content-Type' => 'application/jpg'])->deleteFileAfterSend(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'client_id' => 'required|exists:clients,id',
        ]);
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

                'start_date' => $register->start_date ? date_format(new \DateTime($register->start_date) ,"d-m-Y h:m") : '',
                'end_date' => $register->end_date ? date_format(new \DateTime($register->end_date) ,"d-m-Y h:m") : '',

                'days' => $register->days ?? '',
                'price_per_day' => $register->price_per_day ?? '',
                'total_price' => $register->total_price ?? '',
            ]);
//        return response()->download(storage_path("/files/$filename"), $filename, ['Content-Type' => 'application/jpg'])->deleteFileAfterSend(true);
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
        $register = Register::where('id', $id)->with(['car' => function ($query) {
            $query->withTrashed();
        }, 'client' => function ($query) {
            $query->withTrashed();
        }])->first();
        $templatePath = public_path('kontrata.docx');
        return $this->wordService->fillWordTemplate(
            $templatePath,
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

                'start_date' => $register->start_date ?? '',
                'end_date' => $register->end_date ?? '',

                'days' => $register->days ?? '',
                'price_per_day' => $register->price_per_day ?? '',
                'total_price' => $register->total_price ?? '',
            ]);
    }

}
