<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettlementController extends Controller
{
    public function index()
    {
        $settlements = Cache::remember('settlements_with_users', now()->addMinutes(60), function () {
            return Settlement::with(['user'])->get();
        });

        return view('settlement', compact('settlements'));
    }

    public function startamount()
    {
        return view('addstartamount');
    }

    public function poststart(Request $request)
    {
        $data = $request->validate([
            'start_amount' => 'nullable|numeric',
        ]);

        $user = auth()->user();
        $data['start_time'] = Carbon::now()->toDateTimeString();

        $user->settlements()->create($data);

        Cache::forget('settlements_with_users');
        Cache::remember('settlements_with_users', now()->addMinutes(60), function(){
            return Settlement::all();
        });

        return redirect(route('settlement'))->with('success', 'New settlement created successfully!');
    }

    public function totalamount()
    {
        return view('addtotalamount');
    }

    public function posttotal(Request $request)
    {
        $data = $request->validate([
            'total_amount' => 'nullable|numeric',
        ]);

        $user = auth()->user();
        $latestSettlement = $user->settlements()->latest()->first();

        if (!$latestSettlement) {
            return redirect(route('settlement'))->with('error', 'No active shift found to end.');
        }

        $data['end_time'] = Carbon::now()->toDateTimeString();
        $latestSettlement->update($data);

        Cache::forget('settlements_with_users');
        Cache::forget("settlement_{$latestSettlement->id}");
        Cache::remember('settlements_with_users', now()->addMinutes(60), function(){
            return Settlement::all();
        });

        return redirect(route('settlement'))->with('success', 'Shift ended successfully!');
    }

    public function show($id)
    {
        $settlement = Cache::remember("settlement_{$id}", now()->addMinutes(60), function () use ($id) {
            return Settlement::with('histoys')->find($id);
        });

        return view('showsettlement', compact('settlement'));
    }

    public function destroy($id)
    {
        Settlement::destroy($id);

        Cache::forget('settlements_with_users');

        return redirect(route('settlement'))->with('success', 'Settlement deleted successfully!');
    }
}
