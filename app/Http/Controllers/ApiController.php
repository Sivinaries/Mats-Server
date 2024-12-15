<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Size;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use App\Models\Histoy;
use App\Models\CartMenu;
use App\Models\Category;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function logout(Request $request)
    {
        if ($user = Auth::guard('web')->user()) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });
        }

        Auth::guard('web')->logout();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function category()
    {

        $category = Cache::remember('categories_with_menus', now()->addMinutes(60), function () {
            return Category::with(['menus'])->get();
        });

        return response()->json([
            'category' => $category
        ], 200);
    }

    public function cart()
    {
        // $cart = Cart::with('cartMenus')->get();
        $user = auth()->user();

        $cart = $user->carts()->with(['cartMenus.menu', 'cartMenus.size'])->latest()->first();

        if (!$cart) {
            $cart = $user->carts()->create([]);
        }

        return response()->json([
            'cart' => $cart
        ], 200);
    }

    public function order()
    {
        $orders = Order::with(['cart.user', 'cart.cartMenus.menu', 'cart.cartMenus.size'])->get();
        $statuses = [];

        foreach ($orders as $order) {
            try {
                if ($order->status === 'settlement' && $order->payment_type === 'cash') {
                    $statuses[$order->no_order] = (object) [
                        'status' => $order->status,
                    ];
                    continue;
                }

                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = true;

                $status = \Midtrans\Transaction::status($order->no_order);

                $order->update([
                    'status' => $status->transaction_status,
                    'payment_type' => $status->payment_type ?? null,
                ]);

                if ($status->transaction_status === 'expire') {
                    $order->delete();
                    continue;
                }

                $statuses[$order->no_order] = (object) [
                    'status' => $status->transaction_status,
                ];
            } catch (\Exception $e) {
                $statuses[$order->no_order] = (object) [
                    'status' => 'Error: ' . $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'orders' => $orders,
            'statuses' => $statuses,
            200
        ]);
    }

    public function history()
    {
        $history = Cache::remember('history', now()->addMinutes(60), function () {
            return Histoy::all();
        });

        return response()->json([
            'history' => $history
        ], 200);
    }

    public function settlement()
    {
        $settlement = Cache::remember('settlement_with_users', now()->addMinutes(60), function () {
            return Settlement::with('user')->get();
        });

        return response()->json([
            'settlement' => $settlement
        ], 200);
    }

    public function showproduct($id)
    {
        $menu = Cache::remember("menu_{$id}", now()->addMinutes(60), function () use ($id) {
            return Menu::with('sizes')->find($id);
        });

        return response()->json([
            'menu' => $menu,
        ], 200);
    }

    public function removecart($id)
    {
        $user = auth()->user();
        $cart = $user->carts()->latest()->first();

        $cartMenu = CartMenu::findOrFail($id);

        $subtotal = $cartMenu->subtotal;

        $cartMenu->discount_id;

        $cartMenu->delete();

        $cart->update(['total_amount' => $cart->total_amount - $subtotal]);

        return response()->json([
            'cart' => $cart
        ], 200);
    }

    public function postcart(Request $request)
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

        return response()->json(['message' => 'Item added to cart successfully.'], 200);
    }

    public function postorder(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'atas_nama' => 'required|string|max:255',
        ]);

        $cart = $user->carts()->with('user', 'cartMenus.menu',)->latest()->first();

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'ORDER-' . strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));

        $items = $cart->cartMenus->map(function ($cartMenu) {
            return [
                'id' => $cartMenu->menu_id,
                'price' => (int) $cartMenu->menu->price,
                'quantity' => (int) $cartMenu->quantity,
                'name' => $cartMenu->menu->name,
            ];
        })->toArray();

        $billing_address = [
            'first_name' => $request->atas_nama,
            'last_name' => '',
            'address' => $request->alamat ?? 'N/A',
            'city' => 'N/A',
            'postal_code' => 'N/A',
            'phone' => $request->no_telpon,
            'country_code' => 'IDN',
        ];

        $shipping_address = $billing_address;

        $customer_details = [
            'first_name' => $request->atas_nama,
            'last_name' => '',
            'email' => $user->email,
            'phone' => $request->no_telpon,
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address,
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $cart->total_amount + ($request->ongkir ?? 0),
            ],
            'item_details' => $items,
            'customer_details' => $customer_details,
        ];

        $order = new Order();
        $order->cart_id = $cart->id;
        $order->no_order = $orderId;
        $order->atas_nama = $request->atas_nama;
        $order->save();

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $user->carts()->create();

        return response()->json([
            'snapToken' => $snapToken,
            'order' => $order,
        ], 200);
        // return view('checkout', compact('snapToken', 'order'));
    }
}
