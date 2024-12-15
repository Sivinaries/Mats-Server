<!DOCTYPE html>
<html lang="en">

<head>
    <title>Scanner</title>
    @include('layout.head')
    <style>
        #reader {
            width: 100%;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            overflow: hidden;
        }

        #output {
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
        }
    </style>
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
            <div class='w-full rounded-xl bg-white h-fit mx-auto space-y-4'>
                <div class="p-3">
                    <h1 class="font-extrabold text-3xl text-center">Scan On Here</h1>
                </div>
                <div class="p-4">
                    <div id="reader"></div>
                </div>
            </div>
        </div>
    </main>

    @include('sweetalert::alert')

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`QR Code scanned: ${decodedText}`);

            window.location.href = `/product/${decodedText}/show`;
        }

        function onScanError(errorMessage) {
            console.log(errorMessage);
        }

        const qrCodeScanner = new Html5QrcodeScanner("reader", {
            fps: 20, // Frames per second (how fast the scanner works)
            qrbox: 250, // Size of the scanning box
        });

        qrCodeScanner.render(onScanSuccess, onScanError);
    </script>
</body>

</html>
