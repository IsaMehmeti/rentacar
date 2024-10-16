<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return response()->json([
            "status" => 'success',
            "data" => Car::all(),
            "message" => null
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
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
        ]);
        $car = Car::create($validated);
        return response()->json([
            "status" => 'success',
            "data" => $car,
            "message" => 'U krijua me sukses'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Car $car
     */
    public function update(Request $request, Car $car)
    {
        $car->update($request->all());
        return response()->json([
            "status" => 'success',
            "data" => $car,
            "message" => 'U ndryshua me sukses'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Car $car
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
