<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     */
    public function index()
    {
        $registers = Register::all();

        $total = 0;
        foreach($registers as $register){
            $total+= $register->total_price;
        }
        //get yearly total
        $yearly = 0;
        foreach($registers->where('created_at', '>=', Carbon::now()->startOfYear()) as $register){
            $yearly+= $register->total_price;
        }

        //get today's total
        $today = 0;
        foreach($registers->where('created_at', '>=', Carbon::today()) as $register){
            $today+= $register->total_price;
        }
        return response()->json([
                "status" => 'success',
                "data" => [
                    "today" => $today,
                    "total" => $total,
                    "yearly" => $yearly,
                    "registers" => $registers
                ],
                "message" => null
        ]);
    }
}
