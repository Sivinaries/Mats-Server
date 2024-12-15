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

        Cache::forget('sizes');

        return redirect(route('size'))->with('success', 'Size Sukses Dibuat !');
    }

    public function edit($id)
    {
        $discount = Discount::find($id);
        return view('editdiscount', compact('discount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'percentage' => 'required',
            'percentage' => 'required',
        ]);

        $data = $request->only(['name', 'nominal']);

        Discount::where('id', $id)->update($data);

        Cache::forget('discounts');

        return redirect(route('discount'))->with('success', 'Discount Sukses Diupdate !');
    }

    public function destroy($id)
    {
        Discount::destroy($id);

        Cache::forget('discounts');

        return redirect(route('discount'))->with('success', 'Discount Berhasil Dihapus !');
    }

}
