<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

class CarController extends Controller
{


    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function notify()
    {
        $twilio = new TwilioService();
        $message = $twilio
            ->sendMessage(env('TWILIO_PHONE_TO'), 'Message from Project Rent a car Dushi');
        dd("message", $message);

    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return response()->json(Car::all());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model' => 'required',
            'marsh' => 'sometimes',
            'production_year' => 'sometimes',
            'target' => 'sometimes',
            'shasi_nr' => 'sometimes|unique:cars',
            'owner' => 'sometimes',
            'color' => 'sometimes',
            'comment' => 'sometimes',
            'technical_control' => 'sometimes',
            'registration' => 'sometimes',
        ],
            [
                'shasi_nr.unique' => 'Numri i shasisë ekziston.', // Custom message for unique validation on 'shasi_nr'
                // Add additional custom messages for other fields as needed
            ]);
        if (isset($validated['technical_control'])) {
            $validated['technical_control'] = Carbon::parse($validated['technical_control'])->format('Y-m-d');
        }

        if (isset($validated['registration'])) {
            $validated['registration'] = Carbon::parse($validated['registration'])->format('Y-m-d');
        }

        return response()->json([
            "status" => 'success',
            "data" => Car::create($validated),
            "message" => 'U krijua me sukses'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Car  $car
     */
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'model' => 'required',
            'marsh' => 'sometimes',
            'production_year' => 'sometimes',
            'target' => 'sometimes',
            'shasi_nr' => [
                'sometimes',
                Rule::unique('cars')->ignore($car->id, 'id')
            ],
            'owner' => 'sometimes',
            'color' => 'sometimes',
            'comment' => 'sometimes',
            'technical_control' => 'sometimes',
            'registration' => 'sometimes',
        ],
            [
                'shasi_nr.unique' => 'Numri i shasisë ekziston.', // Custom message for unique validation on 'shasi_nr'
                // Add additional custom messages for other fields as needed
            ]
        );
        if (isset($validated['technical_control'])) {
            $validated['technical_control'] = Carbon::parse($validated['technical_control'])->format('Y-m-d');
        }

        if (isset($validated['registration'])) {
            $validated['registration'] = Carbon::parse($validated['registration'])->format('Y-m-d');
        }

        $car->update($validated);
        return response()->json([
            "status" => 'success',
            "data" => $car,
            "message" => 'U ndryshua me sukses'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Car  $car
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return response()->json([
            "status" => 'success',
            "data" => null,
            "message" => 'U fshi me sukses'
        ]);
    }

    public function uploadFile($reqFile, $car_id)
    {
        $file_path = 'uploads/'.$reqFile->getClientOriginalName();
        $file = new CarImage;
        $file->filename = $reqFile->getClientOriginalName();
        $file->filetype = $reqFile->getClientOriginalExtension();;
        $file->filesize = $reqFile->getSize();;
        $file->filepath = '/uploads';
        $file->file = $file_path;
        $file->originalName = $reqFile->getClientOriginalName();
        $file->car_id = $car_id;
        $file->save();
        $reqFile->move(storage_path('uploads'), $reqFile->getClientOriginalName());
    }

}
