<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Size</title>
    @include('layout.head')
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
                    <h1 class="font-extrabold text-3xl">Edit size</h1>
                </div>
                <div class="p-6">
                    <form class="space-y-3" method="post" action="{{ route('updatesize', ['id' => $size->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Product:</label>
                            <select id="menu" name="menu_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" required>
                                <option></option>
                                @foreach ($products as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Size:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="size" name="size" value="{{ $size->size }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Stock:</label>
                            <input type="number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="stock" name="stock" value="{{ $size->stock }}" required>
                        </div>
                        <button type="submit"
                            class="bg-blue-500 text-white p-4 w-full hover:text-black rounded-lg">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @include('layout.script')

</body>
</html>
