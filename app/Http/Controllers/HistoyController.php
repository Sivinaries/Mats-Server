<?php

namespace App\Http\Controllers;

use App\Models\Histoy;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;
use Illuminate\Support\Facades\Cache;

class HistoyController extends Controller
{
    public function index()
    {
        $history = Cache::remember('history', now()->addMinutes(60), function () {
            return Histoy::all();
        });

        return view('history', ['history' => $history]);
    }

    public function exportOrders(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
        ]);

        $month = $request->month;
        $history = Histoy::whereMonth('created_at', $month)->get();

        return Excel::download(new OrderExport($history, $month), 'history_' . $month . '.xlsx');
    }
}
