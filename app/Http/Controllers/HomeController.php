<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $registers = Register::all();

        $total = 0;
        foreach($registers as $register){
            $total+= $register->total_price;
        }
        $yearly = 0;
        foreach($registers->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', '2021-01-01'), Carbon::createFromFormat('Y-m-d', '2022-01-01')]) as $register){
            $yearly+= $register->total_price;
        }
        $today = 0;
        foreach($registers->where('created_at', Carbon::today()) as $register){
            $today+= $register->total_price;
        }

        return view('home', compact('total', 'yearly', 'today', 'registers'));
    }
}
