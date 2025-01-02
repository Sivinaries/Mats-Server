<!-- resources/views/emails/invoice.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice for Order {{ $order->no_order }}</title>
</head>
<body>
    <h1>Invoice for Order: {{ $order->no_order }}</h1>

    <p><strong>Name:</strong> {{ $order->atas_nama }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Payment Type:</strong> {{ $order->payment_type }}</p>
    <p><strong>Total:</strong>  Rp. {{ number_format($order->cart->total_amount, 0, ',', '.') }}</p>
    

    <h2>Order Details:</h2>
    <table border="1" cellspacing="0" cellpadding="10">
        <thead>
            <tr>
                <th>Menu Item</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->cart->cartMenus as $cartMenu)
                <tr>
                    <td>{{ $cartMenu->menu->name }}</td>
                    <td>{{ $cartMenu->quantity }}</td>
                    <td>{{ $cartMenu->menu->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Thank you for your order!</p>
</body>
</html>
