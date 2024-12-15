<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    @include('layout.head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>

</head>

<body>

    <div class="h-screen w-full mx-auto bg-gradient-to-b from-black to-gray-100 flex items-center p-4 xl:p-0 2xl:p-0">
        <div
            class="h-screen w-full mx-auto bg-gradient-to-b from-black to-gray-800 flex items-center p-4 xl:p-0 2xl:p-0">
            <div class="mx-auto sm:max-w-sm w-full space-y-3 p-8 bg-white rounded-3xl">
                <div class="space-y-3">
                    <a href="">
                        <div>
                            <h1 class="text-5xl xl:text-6xl 2xl:text-6xl font-extrabold text-black">Mats</h1>
                        </div>
                    </a>
                    <div>
                        <h1 class="text-2xl xl:text-3xl 2xl:text-3xl font-extrabold text-black">Login</h1>
                        <p class="text-black text-lg xl:text-xl 2xl:text-xl font-extralight">Sign in to your account</p>
                    </div>
                </div>
                <form method="post" action="{{ route('signin') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="text-black">Email</label>
                        <input class="w-full p-2 bg-gray-100 rounded-xl" type="email" name="email" required />
                    </div>
                    <div class='space-y-2'>
                        <label for="password" class='text-black'>Password</label>
                        <div class="password-container">
                            <input id="password" class='w-full p-2 bg-gray-100 rounded-xl pr-12' type="password"
                                name="password" required />
                            <i id="toggle-password" class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div>
                            <h1 class="text-black text-base xl:text-lg font-extralight">Don't Have Account?</h1>
                        </div>
                        <div>
                            <a href="{{ route('register') }}">
                                <h1
                                    class="text-black text-base xl:text-lg font-extralight underline hover:font-semibold transition-all delay-100">
                                    Sign Up</h1>
                            </a>
                        </div>
                    </div>
                    <div
                        class='border-4 border-black p-2 rounded-3xl w-3/5 mx-auto hover:scale-110 duration-200 transition-all'>
                        <button name="submit" type="submit"
                            class='flex mx-auto text-black text-xl xl:text-2xl 2xl:text-2xl font-semibold'>
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
    @include('layout.script')

    <script>
        // Password Visibility Toggle for Login
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            this.classList.toggle('fa-eye-slash');
        });
    </script>

</body>

</html>
