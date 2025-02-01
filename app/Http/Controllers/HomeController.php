<?php

namespace App\Http\Controllers;

use App\Models\Car;
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
        $totals = Register::selectRaw('
    SUM(total_price) as total,
    SUM(CASE WHEN created_at >= ? THEN total_price ELSE 0 END) as yearly,
    SUM(CASE WHEN created_at >= ? THEN total_price ELSE 0 END) as monthly,
    SUM(CASE WHEN created_at >= ? THEN total_price ELSE 0 END) as today
', [Carbon::now()->startOfYear(), Carbon::now()->startOfMonth(), Carbon::today()])
            ->first();

        $counts = Register::selectRaw('
    COUNT(*) as total_count,
    COUNT(CASE WHEN created_at >= ? THEN 1 END) as yearly_count,
    COUNT(CASE WHEN created_at >= ? THEN 1 END) as monthly_count,
    COUNT(CASE WHEN created_at >= ? THEN 1 END) as today_count
', [Carbon::now()->startOfYear(), Carbon::now()->startOfMonth(), Carbon::today()])
            ->first();

        return response()->json([
            "status" => 'success',
            "data" => [
                "total" => $totals->total,
                "today" => $totals->today,
                "monthly" => $totals->monthly,
                "yearly" => $totals->yearly,
                "counts" => [
                    "total" => $counts->total_count,
                    "yearly" => $counts->yearly_count,
                    "monthly" => $counts->monthly_count,
                    "today" => $counts->today_count,
                ]
            ],
            "message" => null
        ]);
    }

    public function byCar($carId)
    {
        $totals = Register::where('car_id', $carId)
        ->selectRaw('
            SUM(total_price) as total,
            SUM(CASE WHEN created_at >= ? THEN total_price ELSE 0 END) as yearly,
            SUM(CASE WHEN created_at >= ? THEN total_price ELSE 0 END) as monthly,
            SUM(CASE WHEN created_at >= ? THEN total_price ELSE 0 END) as today
        ', [Carbon::now()->startOfYear(), Carbon::now()->startOfMonth(), Carbon::today()])
        ->first();

    $counts = Register::where('car_id', $carId)
        ->selectRaw('
            COUNT(*) as total_count,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as yearly_count,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as monthly_count,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as today_count
        ', [Carbon::now()->startOfYear(), Carbon::now()->startOfMonth(), Carbon::today()])
        ->first();

    return response()->json([
        "status" => 'success',
        "data" => [
            "total" => $totals->total,
            "today" => $totals->today,
            "monthly" => $totals->monthly,
            "yearly" => $totals->yearly,
            "counts" => [
                "total" => $counts->total_count,
                "yearly" => $counts->yearly_count,
                "monthly" => $counts->monthly_count,
                "today" => $counts->today_count,
            ]
        ],
        "message" => null
    ]);
    }
}
