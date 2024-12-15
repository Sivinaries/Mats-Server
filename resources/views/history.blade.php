<!DOCTYPE html>
<html lang="en">

<head>
    <title>History</title>
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
            <div class="w-full rounded-xl bg-white h-fit mx-auto">
                <div class="p-3">
                    <div class="flex">
                        <h1 class="font-extrabold text-3xl">History</h1>
                        <form class="space-x-2 p-1" action="{{ route('exportOrders') }}" method="get">
                            @csrf
                            <label for="month" class=""></label>
                            <select name="month" id="month" class="p-2 text-sm text-black bg-gray-100 rounded-xl">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <button type="submit"
                                class="p-2 text-sm text-white hover:text-black bg-green-500 rounded-xl">
                                Export
                            </button>
                        </form>
                    </div>
                </div>
                <div class="p-2">
                    <div class="overflow-auto">
                        <table id="myTable" class="bg-gray-50 border-2">
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
                                @foreach ($history as $item)
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
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable', {

            });
        });
    </script>
    @include('sweetalert::alert')
    @include('layout.script')

</body>
</html>
