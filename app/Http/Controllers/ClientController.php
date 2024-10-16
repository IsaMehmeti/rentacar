<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "status" => 'success',
            "data" => Client::all(),
            "message" => null
        ]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {

        try {
            Client::create($request->all());

            return response()->json([
                "status" => 'success',
                "data" => null,
                "message" => 'U krijua me sukses'
            ]);
        }catch (\Exception $e){

        }

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     */
    public function update(Request $request, Client $client)
    {
        $client->update($request->all());
        return response()->json([
            "status" => 'success',
            "data" => null,
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
