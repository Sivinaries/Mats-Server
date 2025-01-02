<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profil</title>
    @include('layout.head')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<body class="bg-gray-50">

    @include('layout.sidebar')
    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        @include('layout.navbar')
        <div class="p-5">
            <div class="w-full rounded-xl bg-white h-fit mx-auto">
                <div class="grid grid-cols-1">
                    <div class="p-3">
                        <h1 class="font-extrabold text-3xl">Profil</h1>
                    </div>
                    <div class="p-2">
                        <!-- User Information -->
                        <div class="p-2 space-y-4">
                            <div class="flex justify-between">
                                <h1 class="text-base md:text-xl font-light">Name:</h1>
                                <h1 class="text-base md:text-xl font-bold">{{ auth()->user()->name }}</h1>
                            </div>
                            <div class="flex justify-between">
                                <h1 class="text-base md:text-xl font-light">Email:</h1>
                                <h1 class="text-base md:text-xl font-bold">{{ auth()->user()->email }}</h1>
                            </div>
                            <div class="flex justify-between">
                                <h1 class="text-base md:text-xl font-light">Level:</h1>
                                <h1 class="text-base md:text-xl font-bold">{{ auth()->user()->level }}</h1>
                            </div>
                        </div>
                    </div>
                    <!-- Store Information -->
                    <div>
                        @foreach ($profil as $item)
                            <div class="p-2.5">
                                <div class="">
                                    <h1 class="font-extrabold text-3xl">Store</h1>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="p-2 space-y-4">
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Name:</h1>
                                        <h1 class="text-base md:text-xl font-bold">{{ $item->name }}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Address:</h1>
                                        <h1 class="text-base md:text-xl font-bold">{{ $item->alamat }}</h1>
                                    </div>
                                    <div id="map" class="w-full h-64 rounded-lg z-10"></div> <!-- Map Container -->
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Open:</h1>
                                        <h1 class="text-base md:text-xl font-bold">{{ $item->jam }}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Contact:</h1>
                                        <h1 class="text-base md:text-xl font-bold">{{ $item->no_wa }}</h1>
                                    </div>
                                    <div class="flex justify-between">
                                        <h1 class="text-base md:text-xl font-light">Description:</h1>
                                        <h1 class="text-base md:text-xl font-bold">{{ $item->deskripsi }}</h1>
                                    </div>
                                    <div class="w-full p-4 bg-blue-500 rounded-lg text-center">
                                        <a class=" text-white hover:text-black text-center"
                                        href="{{ route('editprofil', ['id' => $item->id]) }}">Edit store</a>
                                    </div>        
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('sweetalert::alert')
    @include('layout.script')


    <!-- Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Encode the address for use in a URL
            var address = encodeURIComponent("{{ $item->alamat }}");

            // Geocode the address using Nominatim
            fetch(`https://nominatim.openstreetmap.org/search?q=${address}&format=json&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Retrieve latitude and longitude from the geocoding response
                        var latitude = data[0].lat;
                        var longitude = data[0].lon;

                        // Initialize the Leaflet map with the obtained coordinates
                        var map = L.map('map').setView([latitude, longitude], 13);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(map);

                        // Add a marker at the obtained coordinates
                        L.marker([latitude, longitude]).addTo(map)
                            .bindPopup('<b>{{ $item->name }}</b><br>{{ $item->alamat }}')
                            .openPopup();
                    } else {
                        console.warn('No coordinates found for the provided address.');
                    }
                })
                .catch(error => console.error('Error fetching coordinates:', error));
        });
    </script>
</body>

</html>
