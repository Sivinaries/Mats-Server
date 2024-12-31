<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Size;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use App\Models\Histoy;
use App\Models\CartMenu;
use App\Models\Category;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    //LOGIN
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

    //SEARCH
    public function search(Request $request)
    {
        $query = $request->input('q');
        $category = Category::with('menus')
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhereHas('menus', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json([
            'category' => $category
        ], 200);
    }

    //CATEGORY
    public function category()
    {
        $category = Category::with(['menus'])->get();

        return response()->json([
            'category' => $category
        ], 200);
    }

    //HISTORY
    public function history()
    {
        $history = Histoy::all();

        return response()->json([
            'history' => $history
        ], 200);
    }

    //SETTLEMENT
    public function settlement()
    {
        $settlement = Settlement::with('user')->get();

        return response()->json([
            'settlement' => $settlement
        ], 200);
    }

    //CART
    public function cart()
    {
        $user = auth()->user();

        $cart = $user->carts()->with(['cartMenus.menu', 'cartMenus.size'])->latest()->first();

        if (!$cart) {
            $cart = $user->carts()->create([]);
        }

        return response()->json([
            'cart' => $cart
        ], 200);
    }

    public function removecart($id)
    {
        $user = auth()->user();
        $cart = $user->carts()->latest()->first();

        $cartMenu = CartMenu::findOrFail($id);

        $subtotal = $cartMenu->subtotal;

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
                'subtotal' => $subtotal,
            ]);
        }

        $cart->update(['total_amount' => $cart->total_amount + $subtotal]);

        return response()->json(['message' => 'Item added to cart successfully.'], 200);
    }

    //ORDER
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

    public function postorder(Request $request)
    {
        $user = auth()->user();

        $cart = $user->carts()->with('user', 'cartMenus.menu')->latest()->first();

        $request->validate([
            'atas_nama' => 'required|string|max:255',
        ]);

        $orderId = 'ORDER-' . strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));

        $order = new Order();
        $order->cart_id = $cart->id;
        $order->no_order = $orderId;
        $order->status = 'Pending';
        $order->payment_type = 'Pending';
        $order->atas_nama = $request->atas_nama;
        $order->save();

        return response()->json([
            'order' => $order,
        ], 200);
    }

    public function showorder($id)
    {
        $order = Order::find($id);

        return response()->json([
            'order' => $order,
        ], 200);
    }

    public function archive($id)
    {
        $order = Order::with('cart.cartMenus.menu', 'cart.cartMenus.size')->find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Order tidak ditemukan!');
        }

        $user = auth()->user();

        $settlement = $user->settlements()->latest()->first();

        if (!$settlement) {
            $settlement = new Settlement();
            $settlement->user_id = $user->id;
            $settlement->start_time = now();
            $settlement->start_amount = 0;
            $settlement->total_amount = 0;
            $settlement->expected = 0;
            $settlement->save();
        }

        DB::transaction(function () use ($order, $settlement) {
            $history = new Histoy();
            $history->id = $order->id;
            $history->no_order = $order->no_order;
            $history->name = $order->atas_nama;
            $orderDetails = '';

            foreach ($order->cart->cartMenus as $cartMenu) {
                $orderDetails .= "{$cartMenu->menu->name} - {$cartMenu->quantity} - {$cartMenu->size->size} - {$cartMenu->notes}";

                if ($cartMenu->size) {
                    $cartMenu->size->decrement('stock', $cartMenu->quantity);
                } else {
                    $cartMenu->menu->decrement('stock', $cartMenu->quantity);
                }
            }

            Cache::put('sizes', Size::all(), now()->addMinutes(60));

            $history->order = $orderDetails;
            $history->total_amount = $order->cart->total_amount;
            $history->status = $order->status;
            $history->payment_type = $order->payment_type;
            $history->settlement_id = $settlement->id;

            $history->save();

            Cache::forget('history');

            $totalHistoyAmount = $settlement->histoys()->sum('total_amount');
            $settlement->expected = $totalHistoyAmount + $settlement->start_amount;
            $settlement->save();

            Cache::forget('settlements_with_users');

            foreach ($order->cart->cartMenus as $cartMenu) {
                $cartMenu->delete();
            }

            $order->cart->delete();
            $order->delete();
        });

        return response()->json([
            'order' => $order
        ], 200);
    }

    public function destroy($id)
    {
        $order = Order::with('cart.cartMenus.menu', 'cart.cartMenus.size')->find($id);

        if (!$order) {
            return redirect(route('order'))->with('error', 'Order tidak ditemukan!');
        }

        DB::transaction(function () use ($order) {

            $order->cart->cartMenus()->delete();
            $order->cart()->delete();
            $order->delete();

            Cache::put('sizes', Size::all(), now()->addMinutes(60));
        });

        return response()->json([
            'order' => $order
        ], 200);
    }

    //PAYMENT
    public function postcash(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $orderId = $request->input('order_id');
        $order = Order::find($orderId);

        if ($order) {
            $order->status = 'settlement';
            $order->payment_type = 'cash';
            $order->save();

            $newCart = new Cart();
            $newCart->user_id = $order->cart->user_id ?? null;
            $newCart->save();

            return response()->json([
                'message' => 'Payment successful',
                'order' => $order,
            ], 200);
        }

        return response()->json([
            'message' => 'Order not found',
        ], 404);
    }

    public function postonline(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $cart = $order->cart; // Fix relationship to use the correct cart

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = true;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

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
            'city' => 'N/A',
            'postal_code' => 'N/A',
            'country_code' => 'IDN',
        ];

        $shipping_address = $billing_address;

        $customer_details = [
            'first_name' => $request->atas_nama,
            'last_name' => '',
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address,
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $order->no_order, // Use the existing order ID
                'gross_amount' => $cart->total_amount,
            ],
            'item_details' => $items,
            'customer_details' => $customer_details,
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $order->no_order = $params['transaction_details']['order_id'];
        $order->save();

        $newCart = new Cart();
        $newCart->user_id = $order->cart->user_id;
        $newCart->save();

        return response()->json([
            'snapToken' => $snapToken,
            'order' => $order,
        ], 200);
    }

    //PRODUCT
    public function showproduct($id)
    {
        $menu = Menu::with('sizes')->find($id);

        return response()->json([
            'menu' => $menu,
        ], 200);
    }

    //LOGOUT
    public function logout(Request $request)
    {
        if ($user = Auth::guard('web')->user()) {

            $user->tokens()->delete();
        }

        Auth::guard('web')->logout();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
