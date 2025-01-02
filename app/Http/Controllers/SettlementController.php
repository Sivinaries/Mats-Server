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
        $settlements = Cache::remember('settlements', now()->addMinutes(60), function () {
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
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation rules
        ]);

        if ($request->hasFile('img')) {
            $uploadedImage = $request->file('img'); // Get the uploaded file
            $imageName = time() . '_' . $uploadedImage->getClientOriginalName(); // Create a unique name for the image
            $imagePath = $uploadedImage->storeAs('img', $imageName, 'public'); // Store the image in the 'img' folder in the public disk
            $data['img'] = 'img/' . $imageName; // Store the path in the database
        }

        $user = auth()->user();

        $data['start_time'] = Carbon::now()->toDateTimeString();

        $user->settlements()->create($data);

        Cache::forget('settlements');

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

        Cache::forget('settlements');

        return redirect(route('settlement'))->with('success', 'Shift ended successfully!');
    }

    public function show($id)
    {
        $settlement = Settlement::with('histoys')->find($id);

        return view('showsettlement', compact('settlement'));
    }

    public function destroy($id)
    {
        Settlement::destroy($id);

        Cache::forget('settlements');

        return redirect(route('settlement'))->with('success', 'Settlement deleted successfully!');
    }
}
