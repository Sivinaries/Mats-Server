<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\CartMenu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController extends Controller
{
    public function index()
    {
        $menus = Cache::remember('menus', now()->addMinutes(60), function () {
            return Menu::all();
        });

        return view('product', compact('menus'));
    }

    public function create()
    {
        $category = Category::all();

        return view('addproduct', compact('category'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        if ($request->hasFile('img')) {
            $uploadedImage = $request->file('img');
            $imageName = $uploadedImage->getClientOriginalName();
            $imagePath = $uploadedImage->storeAs('img', $imageName, 'public');
            $data['img'] = 'img/' . $imageName;
        }

        $sku = 'SKU-' . strtoupper(uniqid());
        $data['sku'] = $sku;

        $menu = Menu::create($data);

        $qrImageName = $sku . '.svg';
        QrCode::format('svg')->size(200)->generate(route('showproduct', ['id' => $menu->id]), public_path('storage/qr/' . $qrImageName));

        $data['qr_code'] = 'qr/' . $qrImageName;

        $menu->update($data);

        Cache::put('menus', Menu::all(), now()->addMinutes(60));

        Cache::put('categories_with_menus', Category::with('menus')->get(), now()->addMinutes(60));

        return redirect(route('product'))->with('success', 'Product Sukses Dibuat!');
    }

    public function show($id)
    {
        $menu = Cache::remember("menu_{$id}", now()->addMinutes(60), function () use ($id) {
            return Menu::with('sizes')->find($id);
        });

        return view('showproduct', compact('menu'));
    }

    public function edit($id)
    {
        $category = Category::all();

        $menu = Menu::find($id);

        return view('editproduct', compact('menu', 'category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        $menuData = $request->only(['name', 'price', 'img', 'description', 'category_id']);

        if ($request->hasFile('img')) {
            $uploadedImage = $request->file('img');
            $imageName = $uploadedImage->getClientOriginalName();
            $imagePath = $uploadedImage->storeAs('img', $imageName, 'public');
            $menuData['img'] = 'img/' . $imageName;
        }

        Menu::where('id', $id)->update($menuData);

        Cache::put('menus', Menu::all(), now()->addMinutes(60));

        Cache::put('categories_with_menus', Category::with('menus')->get(), now()->addMinutes(60));

        return redirect(route('product'))->with('success', 'Product Sukses Diupdate !');
    }

    public function destroy($id)
    {
        CartMenu::where('menu_id', $id)->delete();
        
        Menu::destroy($id);

        Cache::forget('menus');

        return redirect(route('product'))->with('success', 'Product Berhasil Dihapus !');
    }
}
