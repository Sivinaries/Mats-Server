<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Profil</title>
    @include('layout.head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</head>

<style>
    #mapContainer {
        transition: max-height 0.5s ease;
        overflow: hidden;
        margin-top: 1rem;
    }

    #map {
        min-height: 150px;
        border: 1px solid #ddd;
    }
</style>

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
                    <h1 class="font-extrabold text-3xl">Edit profil</h1>
                </div>
                <div class="p-6">
                    <form class="space-y-3" method="post" action="{{ route('updateprofil', ['id' => $profil->id]) }}">
                        @csrf
                        @method('put')
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Nama profil:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="name" name="name" value="{{ $profil->name }}" required>
                        </div>
                        <!-- Map Container -->
                        <div id="mapContainer" class="space-y-2">
                            <label class="font-semibold text-black">Alamat:</label>
                            <input type="text" id="locationInput" class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" name="alamat"
                                placeholder="Selected Location" value="{{ $profil->alamat }}" readonly />
                            <div class="z-10" id="map"></div>
                            <button onclick="getLocation(event)"
                                class="bg-blue-500 w-full p-4 text-white hover:text-black rounded-lg">
                                Get My Location
                            </button>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Jam buka:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="jam" name="jam" value="{{ $profil->jam }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">No whatsapp:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="no_wa" name="no_wa" value="{{ $profil->no_wa }}" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Deskripsi:</label>
                            <textarea class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full" id="deskripsi" name="deskripsi"
                                required>{{ $profil->deskripsi }}</textarea>
                        </div>
                        <button type="submit"
                            class="bg-blue-500 text-white p-4 w-full hover:text-black rounded-lg">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @include('layout.script')
    <script>
        let map;
        let marker;

        document.addEventListener("DOMContentLoaded", function() {
            initMap();
        });

        function initMap() {
            if (window.mapInitialized) return;
            window.mapInitialized = true;

            map = L.map('map').setView([0, 0], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            marker = L.marker([0, 0], { draggable: true }).addTo(map);

            marker.on('dragend', function() {
                const latLng = marker.getLatLng();
                fetchAddress(latLng.lat, latLng.lng);
            });

            getLocation();
        }

        function getLocation(event) {
            if (event) {
                event.preventDefault();
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        map.setView([lat, lng], 13);
                        marker.setLatLng([lat, lng]);

                        fetchAddress(lat, lng);
                    },
                    function(error) {
                        alert('Unable to retrieve your location. Please check location permissions.');
                    }
                );
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        }

        function fetchAddress(lat, lng) {
            const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const address = data.display_name || 'Address not found';
                    document.getElementById('locationInput').value = address;
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                    document.getElementById('locationInput').value = 'Unable to fetch address';
                });
        }
    </script>

</body>
</html>
