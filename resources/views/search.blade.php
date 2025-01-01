<!DOCTYPE html>
<html lang="en">

<head>
    <title>Search Result</title>
    @include('layout.head')
    <link href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css" rel="stylesheet" />
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
            <div class='w-full bg-white rounded-xl h-fit mx-auto'>
                <div class="p-3 text-center">
                    <h1 class="font-extrabold text-3xl">Search Result</h1>
                </div>
                <div class="p-6">
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <div>
                                <h1 class="font-extrabold text-3xl">Order</h1>
                            </div>
                            <div>
                                <div class="p-2">
                                    <div class="overflow-auto">
                                        <table id="Tableorder" class="bg-gray-50 border-2">
                                            <thead class="w-full">
                                                <th>No</th>
                                                <th>Date</th>
                                                <th>Order Id</th>
                                                <th>Nama</th>
                                                <th>Product</th>
                                                <th>Payment</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($orderResults as $order)
                                                    <tr class="border-2">
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $order->created_at }}</td>
                                                        <td>{{ $order->no_order }}</td>
                                                        <td>{{ $order->atas_nama }}</td>
                                                        <td>
                                                            @foreach ($order->cart->cartMenus as $cartMenu)
                                                                <div class="">
                                                                    <h1>Name: {{ $cartMenu->menu->name }}</h1>
                                                                    <h1>Size: {{ $cartMenu->size->size }}</h1>
                                                                    <h1>Qty: {{ $cartMenu->quantity }}</h1>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $order->payment_type ?? 'N/A' }}</td>
                                                        <td>
                                                            Rp.
                                                            {{ number_format($order->cart->total_amount, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $order->status ?? 'N/A' }}</td>
                                                        <td class="flex gap-2">
                                                            <div class="w-full">
                                                                <form
                                                                    action="{{ route('archive', ['id' => $order->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="p-2 w-full text-white hover:text-black bg-blue-500 rounded-xl text-center">
                                                                        Done
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div class="w-full">
                                                                <form
                                                                    class="p-2 text-white hover:text-black bg-red-500 rounded-xl text-center"
                                                                    method="post"
                                                                    action="{{ route('delorder', ['id' => $order->id]) }}">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit">Delete</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <h1 class="font-extrabold text-3xl">History</h1>
                            </div>
                            <div>
                                <div class="p-2">
                                    <div class="overflow-auto">
                                        <table id="Tablehistory" class="bg-gray-50 border-2">
                                            <thead class="w-full">
                                                <th>No</th>
                                                <th>Date</th>
                                                <th>Order Id</th>
                                                <th>Nama</th>
                                                <th>Order</th>
                                                <th>Payment</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($historyResults as $item)
                                                    <tr class="border-2">
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $item->created_at }}</td>
                                                        <td>{{ $item->no_order }}</td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>
                                                            @php
                                                                $orders = explode(' - ', $item->order);
                                                            @endphp
                                                            @foreach ($orders as $order)
                                                                {{ $order }}
                                                                <br />
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            {{ $item->payment_type }}
                                                        </td>
                                                        <td>Rp. {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                                        <td>
                                                            <h1
                                                                class="p-2 w-full text-white rounded-xl text-center @if ($item->status == 'settlement') bg-green-500 @else @endif">
                                                                {{ $item->status }}
                                                            </h1>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#Tableorder', {
                columnDefs: [{
                    targets: 1, // Index of the 'Date' column
                    render: function(data, type, row) {
                        // Assuming the date is in 'YYYY-MM-DD HH:MM:SS' format
                        var date = new Date(data);
                        return date.toLocaleDateString(); // Format the date as needed
                    },
                }, ],
            });
        });
        $(document).ready(function() {
            let table = new DataTable('#Tablehistory', {
                columnDefs: [{
                    targets: 1, // Index of the 'Date' column
                    render: function(data, type, row) {
                        // Assuming the date is in 'YYYY-MM-DD HH:MM:SS' format
                        var date = new Date(data);
                        return date.toLocaleDateString(); // Format the date as needed
                    },
                }, ],
            });
        });
    </script>
    @include('layout.script')

</body>

</html>
