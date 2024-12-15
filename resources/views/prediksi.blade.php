<!DOCTYPE html>
<html lang="en">

<head>
    <title>Prediction</title>
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
            <div class='w-full rounded-xl h-fit bg-white mx-auto'>
                <div class="p-3">
                    <h1 class="font-extrabold text-3xl">Predict</h1>
                </div>
                <div class="p-2">
                    <div class="">
                        <form class="flex overflow-auto" id="uploadForm">
                            <input class="p-2" type="file" id="fileInput" name="file" accept=".xlsx">
                            <button class="p-2 w-fit text-white hover:text-black bg-blue-500 rounded-xl" type="button"
                                onclick="getPrediction()">Prediction</button>
                        </form>
                    </div>
                    <!-- Display the chart or other elements based on the Flask API response -->
                    <div class="w-full p-8" id="chartContainer"></div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> <!-- Include Axios from CDN -->
            <script>
                async function getPrediction() {
                    const fileInput = document.getElementById('fileInput');
                    const file = fileInput.files[0];

                    const formData = new FormData();
                    formData.append('file', file);

                    try {
                        const response = await axios.post('http://localhost:5000/api', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        });

                        const result = response.data;

                        // Update your HTML elements with the prediction result
                        const chartContainer = document.getElementById('chartContainer');

                        if (result.image_filename) {
                            const imgElement = document.createElement('img');
                            imgElement.src = 'http://localhost:5000/' + result.image_filename;
                            chartContainer.innerHTML = ''; // Clear previous content
                            chartContainer.appendChild(imgElement);
                        } else {
                            console.error('Image filename not found in the response');
                        }
                    } catch (error) {
                        console.error('Error:', error.response ? error.response.data : error.message);
                    }
                }
            </script>
        </div>
    </main>
    @include('layout.script')
</body>
</html>
