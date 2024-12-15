<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add User</title>
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
                    <h1 class="font-extrabold text-3xl">Add user</h1>
                </div>
                <div class="p-6">
                    <form class="space-y-3" method="post" action="{{ route('postuser') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Nama:</label>
                            <input type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="name" name="name" placeholder="Nama" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Email:</label>
                            <input type="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Password:</label>
                            <input type="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="space-y-2">
                            <label class="font-semibold text-black">Password Confirmation:</label>
                            <input type="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 p-2 rounded-lg w-full"
                                id="password" name="password_confirmation" placeholder="Password Confirmation" required>
                        </div>
                        <button type="submit" class="bg-blue-500  text-white p-4 w-full hover:text-black rounded-lg">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @include('layout.script')

</body>
</html>
