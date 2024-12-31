<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\CartMenu;
use App\Models\Size;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        $menus = Menu::all();

        return view('addcart', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'size_id' => 'required|exists:sizes,id',
        ]);

        $user = auth()->user();
        $cart = $user->carts()->latest()->first() ?? $user->carts()->create(['total_amount' => 0]);

        $menu = Menu::findOrFail($request->input('menu_id'));
        $size = Size::findOrFail($request->input('size_id')); // Ensure size exists.

        $quantity = $request->input('quantity');
        $subtotal = $menu->price * $quantity;

        if ($size->stock < $quantity) {
            return back()->withErrors(['stock' => 'Insufficient stock for this size.']);
        }

        
        $existingCartMenu = CartMenu::where('cart_id', $cart->id)
            ->where('menu_id', $menu->id)
            ->where('size_id', $size->id)
            ->where('notes', $request->input('notes'))
            ->first();

        if ($existingCartMenu) {
            $existingCartMenu->quantity += $quantity;
            $existingCartMenu->subtotal += $subtotal;
            $existingCartMenu->save();
        } else {
            CartMenu::create([
                'cart_id' => $cart->id,
                'menu_id' => $menu->id,
                'size_id' => $size->id,
                'quantity' => $quantity,
                'notes' => $request->input('notes'),
                'subtotal' => $subtotal,
            ]);
        }

        $cart->update(['total_amount' => $cart->total_amount + $subtotal]);

        return redirect(route('addorder'));
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $cart = $user->carts()->latest()->first();

        $cartMenu = CartMenu::findOrFail($id);

        $subtotal = $cartMenu->subtotal;

        $cartMenu->delete();

        $cart->update(['total_amount' => $cart->total_amount - $subtotal]);


        return redirect()->route('addorder');
    }
}
