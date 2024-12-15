<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
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

            <!-- chart section -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-2 lg:grid-cols-2 ">
                <!-- chart 1: Total Order -->
                <div class="p-6 bg-white rounded-xl shadow-xl">
                    <h1 class="font-light">Total Order</h1>
                    <i class="fa fa-arrow-up text-lime-500"></i>
                    <canvas id="grafikHistoy" width="100" height="50"></canvas>
                </div>
                <!-- chart 2: Total Revenue -->
                <div class="p-6 bg-white rounded-xl shadow-xl">
                    <h1 class="font-light">Total Revenue</h1>
                    <i class="fa fa-arrow-up text-lime-500"></i>
                    <canvas id="grafikRevenue" width="100" height="50"></canvas>
                </div>
                <!-- chart 3: Settlement -->
                <div class="p-6 bg-white rounded-xl shadow-xl">
                    <h1 class="font-light">Settlement</h1>
                    <i class="fa fa-arrow-up text-lime-500"></i>
                    <label for="dateSelect">Select date:</label>
                    <select class="border bg-gray-100 p-2 rounded-xl" id="dateSelect" onchange="updateChart()">
                        @foreach ($labels3 as $date)
                            <option value="{{ $date }}" {{ $selectedDate == $date ? 'selected' : '' }}>
                                {{ $date }}</option>
                        @endforeach
                    </select>
                    <canvas id="grafikSettlement" width="100" height="50"></canvas>
                </div>
                <!-- chart 4: Total Expense -->
                <div class="p-6 bg-white rounded-xl shadow-xl">
                    <h1 class="font-light">Total Expense</h1>
                    <i class="fa fa-arrow-up text-lime-500"></i>
                    <canvas id="grafikExpense" width="100" height="50"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">
        // CHART 1: Total Orders
        const labels1 = {{ Js::from($labels1) }};
        const data1 = {{ Js::from($data1) }};
        const config1 = {
            type: 'bar',
            data: {
                labels: labels1,
                datasets: [{
                    label: 'Jumlah Order',
                    data: data1,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1000
                    }
                }
            }
        };
        new Chart(document.getElementById('grafikHistoy'), config1);

        // CHART 2: Total Revenue
        const labels2 = {{ Js::from($labels2) }};
        const data2 = {{ Js::from($data2) }};
        const config2 = {
            type: 'bar',
            data: {
                labels: labels2,
                datasets: [{
                    label: 'Jumlah Pemasukan',
                    data: data2,
                    borderColor: 'rgb(75, 192, 192)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5000000
                    }
                }
            }
        };
        new Chart(document.getElementById('grafikRevenue'), config2);

        // CHART 3: Settlement
        const labels3 = {{ Js::from($labels3) }};
        const data3 = {{ Js::from($data3) }};
        let selectedDate = "{{ $selectedDate }}";
        let chart3;

        // Function to update the chart when a different date is selected
        function updateChart() {
            selectedDate = document.getElementById('dateSelect').value;

            // Check if the selected date exists in labels3
            const index = labels3.indexOf(selectedDate);
            const selectedData = index !== -1 ? data3[index] : 0;

            // Update the chart's data
            chart3.data.labels = [selectedDate];
            chart3.data.datasets[0].data = [selectedData];
            chart3.update();
        }

        // Initialization of the chart when the page is first loaded
        document.addEventListener('DOMContentLoaded', function() {
            const initialData = data3[labels3.indexOf(selectedDate)] || 0;

            const dataSet = {
                label: 'Settlement Amount',
                data: [initialData], // Show the selected date's data initially
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
            };

            const config3 = {
                type: 'bar',
                data: {
                    labels: [selectedDate],
                    datasets: [dataSet]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: Math.max(...data3) + 30 // Dynamic max value based on data
                        }
                    }
                }
            };

            // Initialize the chart
            chart3 = new Chart(document.getElementById('grafikSettlement'), config3);
        });

        // CHART 4: Total Expense
        const labels4 = {{ Js::from($labels4) }};
        const data4 = {{ Js::from($data4) }};
        const config4 = {
            type: 'bar',
            data: {
                labels: labels4,
                datasets: [{
                    label: 'Jumlah Expense',
                    data: data4,
                    borderColor: 'rgb(75, 192, 192)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5000000
                    }
                }
            }
        };
        new Chart(document.getElementById('grafikExpense'), config4);
    </script>
    @include('layout.script')
    @include('sweetalert::alert')

</body>

</html>
