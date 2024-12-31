<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Cache::remember('sizes', now()->addMinutes(60), function () {
            return Size::with('menu')->get();
        });

        return view('size', compact('sizes'));
    }

    public function create()
    {
        $products = Menu::select('id', 'name')->get();

        return view('addsize', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_id' => 'required',
            'size' => 'required',
            'stock' => 'required',
        ]);

        Size::create($data);

        Cache::put('sizes', Size::all(), now()->addMinutes(60));

        return redirect(route('size'))->with('success', 'Size Sukses Dibuat !');
    }

    public function edit($id)
    {
        $size = Size::find($id);

        $products = Menu::select('id', 'name')->get();

        return view('editsize', compact('size', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_id' => 'required',
            'size' => 'required',
            'stock' => 'required',
        ]);

        $data = $request->only(['menu_id', 'size', 'stock']);

        Size::where('id', $id)->update($data);

        Cache::put('sizes', Size::all(), now()->addMinutes(60));

        return redirect(route('size'))->with('success', 'Size Sukses Diupdate !');
    }

    public function destroy($id)
    {
        Size::destroy($id);

        Cache::forget('sizes');

        return redirect(route('sizew'))->with('success', 'Size Berhasil Dihapus !');
    }

}
