<!DOCTYPE html>
<html lang="en">

<head>
    <title>Checkout</title>
    @include('layout.head')
    <script type="text/javascript" src="https://app.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-KrOCGoZpRFFFE4Ey"></script>
</head>

<body class="bg-gray-50">
    <!-- sidenav  -->
    @include('layout.sidebar')
    <!-- end sidenav -->
    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        <!-- Navbar -->
        @include('layout.navbar')
        <!-- end Navbar -->
        <div class="p-5">
            <div class='w-full rounded-xl bg-white h-fit mx-auto'>
                <div class="p-6 text-center">
                    <h1 class="font-extrabold text-3xl">Checkout</h1>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2">
                        <label class="font-semibold text-black text-lg">Atas nama:</label>
                        <input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                            value="{{ $order->atas_nama }}" readonly>
                    </div>
                    <div class="space-y-2">
                        <label class="font-semibold text-black text-lg">No Telpon:</label>
                        <input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                            value="{{ $order->no_telpon }}" readonly>
                    </div>
                    <div class="grid grid-cols-2 gap-2 p-3">
                        @foreach ($order->cart->cartMenus as $menu)
                            <div class=''>
                                <img src="{{ asset('storage/img/' . basename($menu->menu->img)) }}" alt="Product Image"
                                    class='mx-auto my-auto w-14 h-17' />
                            </div>
                            <div class='flex justify-between'>
                                <h1 class='font-bold text-sm xl:text-lg'>{{ $menu->menu->name }}</h1>
                                <h1 class='font=bold text-sm xl:text-lg'>X</h1>
                                <h1 class='font-bold text-sm xl:text-lg'>{{ $menu->quantity }}</h1>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-2">
                        <label class="font-semibold text-black text-lg">Cash Amount:</label>
                        <input type="number" id="cash-input" name="cash_amount"
                            class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" required>
                    </div>
                    <div class="flex justify-between border-t p-2 xl:p-8">
                        <div class="flex gap-2 my-auto">
                            <label class="text-black font-bold text-2xl">Exchange:</label>
                            <h1 id="change-display" class="text-2xl">Rp. 0</h1>
                        </div>
                        <div class="flex gap-2 my-auto">
                            <label class="text-black font-bold text-2xl">Total:</label>
                            <h1 class="text-2xl">
                                {{ $order->cart->total_amount > 0 ? 'Rp. ' . number_format($order->cart->total_amount, 0, ',', '.') : 'Disabled' }}
                            </h1>
                        </div>
                        <div class="flex gap-4">
                            <button id="pay-button" type="button"
                                class="bg-blue-500 text-xl text-white p-2 w-fit hover:text-black rounded-lg">Online
                                Payment</button>
                            <form action="{{ route('cashpayment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <div class="space-y-2 ">
                                    <button type="submit"
                                        class="bg-green-500 text-xl text-white p-2 w-fit hover:text-black rounded-lg"
                                        id="cash-button">Cash Payment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script type="text/javascript">
        // Disable browser's back functionality
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            window.history.pushState(null, null, window.location.href);
        };

        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function(event) {
            event.preventDefault();
            @if (isset($snapToken))
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        console.log('Payment successful!', result);
                        window.location.href = '{{ route('order') }}'; // Redirect to dashboard
                    },
                    onPending: function(result) {
                        console.log('Payment pending', result);
                        window.location.href = '{{ route('order') }}'; // Redirect to dashboard
                    },
                    onError: function(result) {
                        console.error('Payment failed', result);
                        window.location.href = '{{ route('order') }}'; // Redirect to dashboard
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        window.location.href = '{{ route('order') }}'; // Redirect to dashboard
                    }
                });
            @else
                console.error('Snap token is not set!');
            @endif
        });

        // Handle cash input change and calculate change
        document.getElementById('cash-input').addEventListener('input', function() {
            var cashAmount = parseFloat(this.value) || 0;
            var totalAmount = parseFloat("{{ $order->cart->total_amount }}");
            var change = cashAmount - totalAmount;

            // Display change
            document.getElementById('change-display').textContent = 'Rp. ' + (change >= 0 ? numberWithCommas(change
                .toFixed(0)) : '0');
        });

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
        @include('layout.script')

</body>

</html>
