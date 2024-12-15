<!DOCTYPE html>
<html lang="en">

<head>
    <title>Inventory</title>
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
                    <div class="">
                        <h1 class="font-extrabold text-3xl">Inventory</h1>
                    </div>
                </div>
                <div class="p-2">
                    <div class="overflow-auto">
                        <table id="myTable" class="bg-gray-50 border-2">
                            <thead class="w-full">
                                <th>No</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($invents as $item)
                                    <tr class="border-2">
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->created_at ?? 'N/A' }}</td>
                                        <td>{{ $item->menu->name ?? 'N/A' }}</td>
                                        <td>{{ $item->size ?? 'N/A' }}</td>
                                        <td>{{ $item->stock ?? 'N/A' }}</td>
                                        <td class="flex gap-2">
                                            <div class="w-full">
                                                <a href="{{ route('editsize', ['id' => $item->id]) }}">
                                                    <h1
                                                        class="p-2 text-white hover:text-black bg-blue-500 rounded-xl text-center">
                                                        Edit</h1>
                                                </a>
                                            </div>
                                            <div class="w-full">
                                                <form
                                                    class="p-2 text-white hover:text-black bg-red-500 rounded-xl text-center"
                                                    method="post"
                                                    action="{{ route('delsize', ['id' => $item->id]) }}">
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
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable', {
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
    @include('sweetalert::alert')
    @include('layout.script')

</body>
</html>
