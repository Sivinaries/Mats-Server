<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Size;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use App\Models\Histoy;
use App\Mail\InvoiceMail;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['cart.user', 'cart.cartMenus.menu', 'cart.cartMenus.size'])->get();
        $statuses = [];

        foreach ($orders as $order) {
            // Skip orders with 'settlement' status and 'cash' payment type
            if ($order->status === 'settlement' && $order->payment_type === 'cash') {
                $statuses[$order->no_order] = (object) [
                    'status' => $order->status,
                ];
                continue;
            }

            // Set up Midtrans config
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = true;

            try {
                // Get transaction status from Midtrans
                $status = \Midtrans\Transaction::status($order->no_order);

                // Update order status based on Midtrans response
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
                \Log::error("Midtrans API Error for Order: {$order->no_order}, Error: {$e->getMessage()}");
                $statuses[$order->no_order] = (object) [
                    'status' => 'error', // Or handle differently
                ];
            }
        }

        return view('order', compact('orders', 'statuses'));
    }

    public function create()
    {
        $user = auth()->user();

        $cart = $user->carts()->latest()->first();

        if (!$cart) {
            $cart = $user->carts()->create([]);
        }

        return view('addorder', compact('cart'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $cart = $user->carts()->with('user', 'cartMenus.menu')->latest()->first();

        $request->validate([
            'atas_nama' => 'required|string|max:255',
            'email' => 'required',
        ]);

        $orderId = 'ORDER-' . strtoupper(substr(Uuid::uuid4()->toString(), 0, 8));

        $order = new Order();
        $order->cart_id = $cart->id;
        $order->no_order = $orderId;
        $order->status = 'Pending';
        $order->payment_type = 'Pending';
        $order->atas_nama = $request->atas_nama;
        $order->email = $request->email;
        $order->save();

        return view('checkout', compact('order'));
    }

    public function onlinepayment(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $order = Order::find($orderId);

            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $cart = $order->cart;

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
                'first_name' => $order->atas_nama,
                'last_name' => '',
                'city' => 'N/A',
                'postal_code' => 'N/A',
                'country_code' => 'IDN',
            ];

            $customer_details = [
                'first_name' => $order->atas_nama,
                'last_name' => '',
                'billing_address' => $billing_address,
                'shipping_address' => $billing_address,
            ];

            $params = [
                'transaction_details' => [
                    'order_id' => $order->no_order,
                    'gross_amount' => $cart->total_amount,
                ],
                'item_details' => $items,
                'customer_details' => $customer_details,
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->no_order = $params['transaction_details']['order_id'];
            $order->status = 'Pending';
            $order->payment_type = 'Pending';
            $order->save();

            $newCart = new Cart();
            $newCart->user_id = $order->cart->user_id;
            $newCart->save();

            Mail::to($order->email)->send(new InvoiceMail($order));

            return response()->json([
                'snapToken' => $snapToken,
                'order' => $order,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Payment Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred during the payment process.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function cashpayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::find($orderId);

        if ($order) {
            $order->status = 'Settlement';
            $order->payment_type = 'Cash';
            $order->save();

            $newCart = new Cart();
            $newCart->user_id = $order->cart->user_id;
            $newCart->save();

            Mail::to($order->email)->send(new InvoiceMail($order));

            return redirect()->route('order')->with('success', 'Cash payment successful!');
        }

        return redirect()->route('order')->with('error', 'Cash payment failed!');
    }

    public function edcpayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::find($orderId);

        if ($order) {
            $order->status = 'Settlement';
            $order->payment_type = 'EDC';
            $order->save();

            $newCart = new Cart();
            $newCart->user_id = $order->cart->user_id;
            $newCart->save();

            Mail::to($order->email)->send(new InvoiceMail($order));

            return redirect()->route('order')->with('success', 'Cash payment successful!');
        }

        return redirect()->route('order')->with('error', 'Cash payment failed!');
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

            Cache::forget('sizes');

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

        return redirect()->back()->with('success', 'Order berhasil diarsipkan dan stok diperbarui!');
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

            Cache::forget('sizes');
        });

        return redirect(route('order'))->with('success', 'Order berhasil dihapus dan stok dikembalikan!');
    }
}
