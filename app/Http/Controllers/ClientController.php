<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Client::withCount('registers')->get());
    }

    public function clientRegisters($id)
    {
        $client = Client::findOrFail($id);
        return response()->json([
            "status" => 'success',
            "data" => [
                "client" => $client,
                "registers" => $client->registers
            ],
            "message" => null
        ]);

    }

    public function searchByName(Request $request)
    {
        $name = $request->query('name');
        if (!$name) {
            return Client::latest()
                ->whereNotNull('phone')
                ->whereNotNull('id_card')
                ->whereNotNull('drivers_license_id')
                ->take(5)->get();
        }

        $clients = Client::where('full_name', 'like', '%'.$name.'%')
            ->whereNotNull('phone')
            ->whereNotNull('id_card')
            ->whereNotNull('drivers_license_id')
            ->take(5)->get();

        return response()->json($clients);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $validated = $request->validate
        (
            [
                'full_name' => 'required',
                'address' => 'sometimes',
                'phone' => 'sometimes',
                'id_card' => 'required|unique:clients',
                'birth' => 'sometimes',
                'color' => 'sometimes',
                'birthplace' => 'sometimes',
                'drivers_license_id' => 'required|unique:clients',
            ],
            [
                'id_card.unique' => 'Numri i letërnjoftimit ekziston.',
                'drivers_license_id.unique' => 'Patentë shoferi ekziston.',
            ]
        );

        return response()->json([
            "status" => 'success',
            "data" => Client::create($validated),
            "message" => 'U krijua me sukses'
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'full_name' => 'required',
            'address' => 'sometimes',
            'phone' => 'sometimes',
            'drivers_license_id' => [
                'required',
            ],
            'id_card' => [
                'required',
            ],
            'birth' => 'sometimes',
            'color' => 'sometimes',
            'birthplace' => 'sometimes',

        ],
            [
                'id_card.unique' => 'Numri i letërnjoftimit ekziston.',
                'drivers_license_id.unique' => 'Patentë shoferi ekziston.',
            ]
        );

        return response()->json([
            "status" => 'success',
            "data" => $client->update($validated),
            "message" => 'U ndryshua me sukses'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json([
            "status" => 'success',
            "data" => null,
            "message" => 'U fshi me sukses'
        ]);
    }
}
