<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Product</title>
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
                    <h1 class="font-extrabold text-3xl">Add product</h1>
                </div>
                <div class="p-6">
                    <form class="space-y-3" method="post" action="{{ route('postproduct') }} "
                        enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-2">
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Nama produk:</label>
                                <input type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                    id="name" name="name" placeholder="Nama produk" required>
                            </div>
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Harga produk:</label>
                                <input type="number"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                    id="price" name="price" placeholder="Harga produk" required>
                            </div>
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Kategori:</label>
                                <select id="category" name="category_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                    required>
                                    <option></option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Deskripsi produk:</label>
                            <textarea class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" id="description"
                                name="description" placeholder="Description produk" required></textarea>
                            <p class="text-gray-500 text-right"><span id="charCount"></span>/200 characters</p>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Gambar produk:</label>
                            <input type="file"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="img" name="img" required>
                        </div>
                        <button type="submit"
                            class="bg-blue-500 text-white p-4 w-full hover:text-black rounded-lg">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.getElementById('description').addEventListener('input', function() {
            var maxLength = 200;
            var currentLength = this.value.length;
    
            document.getElementById('charCount').innerText = currentLength + '/' + maxLength;
    
            if (currentLength >= maxLength) {
                this.setAttribute('disabled', true);
            } else {
                this.removeAttribute('disabled');
            }
        });
    </script>    
    @include('layout.script')

</body>
</html>
