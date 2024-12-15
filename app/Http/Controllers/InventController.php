<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Invent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InventController extends Controller
{
    public function index()
    {
        $invents = Cache::remember('sizes', now()->addMinutes(60), function () {
            return Size::with('menu')->get();
        });

        return view('inventory', compact('invents'));
    }
}
