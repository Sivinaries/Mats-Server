<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Settlement</title>
    @include('layout.head')
</head>

<body class="bg-gray-50">

    <!-- sidenav -->
    @include('layout.sidebar')
    <!-- end sidenav -->

    <main class="md:ml-64 xl:ml-72 2xl:ml-72">
        <!-- Navbar -->
        @include('layout.navbar')
        <!-- end Navbar -->
        <div class="p-5">
            <div class="w-full bg-white rounded-xl h-fit mx-auto">
                <div class="p-3 text-center">
                    <h1 class="font-extrabold text-3xl">Add Settlement</h1>
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

                    <form class="space-y-3" method="post" action="{{ route('poststart') }}"
                        enctype="multipart/form-data">
                        @csrf @method('post')

                        <div class="space-y-2">
                            <label class="font-semibold text-black">Start Amount:</label>
                            <input type="number"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="start_amount" name="start_amount" placeholder="Start Amount" required />
                            <p id="output">Rp.</p>
                        </div>

                        <div class="space-y-2">
                            <video id="camera" class="mx-auto" width="500" height="500" autoplay></video>
                            <button class="bg-red-500 text-white p-4 w-full hover:text-black rounded-lg" type="button" id="captureButton">Capture Image</button>
                            <input type="file" id="capturedImage" name="img" style="display: none;">
                        </div>

                        <button type="submit" class="bg-blue-500 text-white p-4 w-full hover:text-black rounded-lg">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('layout.script')

    <script>
        const input = document.getElementById('start_amount');
        const output = document.getElementById('output');

        function formatToIDR(value) {
            if (!value) return 'Rp. 0';
            return 'Rp. ' + parseInt(value.replace(/[^\d]/g, '')).toLocaleString('id-ID');
        }

        input.addEventListener('input', () => {
            const formatted = formatToIDR(input.value);
            output.textContent = formatted;
        });
        const videoElement = document.getElementById('camera');
const captureButton = document.getElementById('captureButton');
const capturedImageInput = document.getElementById('capturedImage');
let stream;

// Access the camera
navigator.mediaDevices.getUserMedia({
    video: true
})
.then((mediaStream) => {
    stream = mediaStream;
    videoElement.srcObject = stream;
})
.catch((err) => {
    console.error('Error accessing camera: ', err);
});

captureButton.addEventListener('click', function() {
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    canvas.width = videoElement.videoWidth;
    canvas.height = videoElement.videoHeight;
    context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

    // Convert the captured image to a blob and create a file from it
    canvas.toBlob(function(blob) {
        const file = new File([blob], 'captured-image.jpg', {
            type: 'image/jpeg'
        });

        // Create a new DataTransfer object to set the files on the input element
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        capturedImageInput.files = dataTransfer.files; // Set the captured file as the input's files

        // Optionally, you can check the file here for debugging
        console.log(capturedImageInput.files);
    });

    // Stop the video stream after capturing the image
    const tracks = stream.getTracks();
    tracks.forEach(track => track.stop()); // Stop all media tracks

    // Change capture button background to bg-gray
    captureButton.classList.remove('bg-red-500');
    captureButton.classList.add('bg-gray-300');
    captureButton.disabled = true;  // Optional: disable the button to prevent further captures
});

    </script>
</body>

</html>
