<!DOCTYPE html>
<html lang="en">

<head>
    <title>Settlement</title>
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
            <div class='w-full rounded-xl bg-white h-fit mx-auto'>
                <div class="p-3">
                    <div class="block md:flex 2xl:flex justify-between space-y-2 md:space-y-0">
                        <h1 class="font-extrabold text-3xl">Settlements</h1>
                        <div class="flex gap-2">
                            <a class="p-2 bg-blue-500 rounded-xl text-white hover:text-black text-center"
                                href="{{ route('addstartamount') }}">Start
                                shift</a>
                            <a class="p-2 bg-green-500 rounded-xl text-white hover:text-black text-center"
                                href="{{ route('addtotalamount') }}">End
                                shift</a>
                        </div>
                    </div>
                </div>
                <div class="p-2">
                    <div class="overflow-auto">
                        <table id="myTable" class="bg-gray-50 border-2">
                            <thead class="w-full">
                                <th>No</th>
                                <th>Name</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Start Amount</th>
                                <th>Total Amount</th>
                                <th>Expected</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($settlements as $item)
                                    <tr class="border-2">
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->start_time }}</td>
                                        <td>{{ $item->end_time }}</td>
                                        <td>Rp. {{ number_format($item->start_amount, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($item->expected, 0, ',', '.') }}</td>
                                        <td class="flex  gap-2">
                                            <a href="{{ route('showsettlement', ['id' => $item->id]) }}">
                                                <h1
                                                    class="p-2 w-full text-white hover:text-black bg-blue-500 rounded-xl text-center">
                                                    Detail</h1>
                                            </a>
                                            <form
                                                class="p-2 w-full text-white hover:text-black bg-red-500 rounded-xl text-center"
                                                method="post"
                                                action="{{ route('delsettlement', ['id' => $item->id]) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit">Delete</button>
                                            </form>
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
