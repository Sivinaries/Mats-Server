<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Product</title>
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
                    <h1 class="font-extrabold text-3xl">Edit product</h1>
                </div>
                <div class="p-6">
                    @if ($errors->any())
                        <div class="bg-red-200 text-red-800 p-4 rounded-lg mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="space-y-3" method="post" action="{{ route('updateproduct', ['id' => $menu->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <input type="hidden" name="old_id" value="{{ old('old_id', $menu->id) }}">
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-2">
                            <!-- Nama Produk -->
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Nama produk:</label>
                                <input type="text"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                    id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                            </div>

                            <!-- Harga Produk -->
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Harga produk:</label>
                                <input type="number"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                    id="price" name="price" value="{{ old('price', $menu->price) }}" required>
                                <p id="priceOutput">Rp.</p>
                            </div>

                            <!-- Kategori -->
                            <div class="space-y-2">
                                <label class="font-semibold text-black">Kategori:</label>
                                <select id="category" name="category_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                    required>
                                    <option></option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $menu->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Deskripsi Produk -->
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Deskripsi produk:</label>
                            <textarea class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" id="description"
                                name="description" maxlength="200" required>{{ old('description', $menu->description) }}</textarea>
                            <p class="text-gray-500 text-right"><span
                                    id="charCount">{{ strlen(old('description', $menu->description)) }}</span>/200
                                characters</p>
                        </div>

                        <!-- Gambar Produk -->
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Gambar produk:</label>
                            <input type="file"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="img" name="img" value="{{ old('img', $menu->img) }}">

                                <div class="">
                                    <img src="{{ asset('storage/' . $menu->img) }}" 
                                        alt="Current Product Image"
                                        class="w-96 h-80">
                                </div>
                        </div>

                        <button type="submit"
                            class="bg-blue-500 text-white p-4 w-full hover:text-black rounded-lg">Submit</button>
                    </form>
                    @if ($errors->any())
                        <div class="bg-red-200 text-red-800 p-4 rounded-lg mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
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

        const priceInput = document.getElementById('price');
        const priceOutput = document.getElementById('priceOutput');

        // Function to format number to IDR currency
        function formatToIDR(value) {
            if (!value || isNaN(value)) return 'Rp. 0';
            return 'Rp. ' + parseInt(value).toLocaleString('id-ID');
        }

        // Event listener for input formatting
        priceInput.addEventListener('input', () => {
            const formattedPrice = formatToIDR(priceInput.value);
            priceOutput.textContent = formattedPrice;
        });

        // Initialize priceOutput on page load if there's already a value
        document.addEventListener('DOMContentLoaded', () => {
            priceOutput.textContent = formatToIDR(priceInput.value);
        });
    </script>
    @include('layout.script')

</body>

</html>
