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
     * @return View
     */
    public function index(): View
    {
        $cars = Car::all();
        return view('car.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('car.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $car = Car::create($request->all());
        if ($request->file('file')){
            $this->uploadFile($request->file('file'), $car->id);
        }
        return redirect()->back()->with('status', 'U krijua me sukses');
    }

    /**
     * Display the specified resource.
     *
     * @param Car $car
     * @return Response
     */
    public function show(Car $car)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Car $car
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(Car $car)
    {
        return view('car.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Car $car
     * @return RedirectResponse
     */
    public function update(Request $request, Car $car)
    {
        $car->update($request->all());
        return redirect()->back()->with('status', 'U ndryshua me sukses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Car $car
     * @return RedirectResponse
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->back()->with('danger', 'U fshi me sukses');
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
