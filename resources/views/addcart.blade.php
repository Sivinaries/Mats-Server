<!DOCTYPE html>
<html lang="en">

<head>
    <title>Products</title>
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
            <div class='w-full rounded-xl bg-white h-fit mx-auto'>
                <div class="p-3">
                    <div class="p-3 text-center">
                        <h1 class="font-extrabold text-3xl">Create Order</h1>
                    </div>
                    <div class="grid grid-cols-2 xl:grid-cols-6 lg:grid-cols-6 gap-2 p-2">
                        @foreach ($menus as $menu)
                            <div class='w-full'>
                                <a href="{{ route('showproduct', ['id' => $menu->id]) }}">
                                    <div class='p-2 rounded-xl relative bg-red-900 space-y-2'>
                                        <div class='space-y-2'>
                                            <div class='bg-gray-100 p-2 rounded-xl '>
                                                <img src="{{ asset('storage/img/' . basename($menu->img)) }}"
                                                    alt="Product Image"
                                                    class='mx-auto my-auto w-14 h-17 rounded-xl relative' />
                                            </div>
                                            <div class='space-y-1'>
                                                <h1 class='font-extrabold text-sm text-white'>{{ $menu->name }}</h1>
                                                <p class='font-light text-sm text-white line-clamp-1'>
                                                    {{ $menu->description }}
                                                </p>
                                                <div class='flex space-x-2'>
                                                    <svg width="15" height="18" viewBox="0 0 13 12"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clipPath="url(#clip0_128_11)">
                                                            <path
                                                                d="M6.09426 7.25C5.62721 7.25 5.17929 7.43437 4.84904 7.76256C4.51879 8.09075 4.33325 8.53587 4.33325 9C4.33325 9.46413 4.51879 9.90925 4.84904 10.2374C5.17929 10.5656 5.62721 10.75 6.09426 10.75C6.56131 10.75 7.00922 10.5656 7.33948 10.2374C7.66973 9.90925 7.85526 9.46413 7.85526 9C7.85526 8.53587 7.66973 8.09075 7.33948 7.76256C7.00922 7.43437 6.56131 7.25 6.09426 7.25ZM5.33954 9C5.33954 8.80109 5.41906 8.61032 5.56059 8.46967C5.70213 8.32902 5.89409 8.25 6.09426 8.25C6.29442 8.25 6.48639 8.32902 6.62792 8.46967C6.76946 8.61032 6.84898 8.80109 6.84898 9C6.84897 9.19891 6.76946 9.38968 6.62792 9.53033C6.48639 9.67098 6.29442 9.75 6.09426 9.75C5.89409 9.75 5.70213 9.67098 5.56059 9.53033C5.41906 9.38968 5.33954 9.19891 5.33954 9Z"
                                                                fill="#42FF00" />
                                                            <path
                                                                d="M8.87468 3.55797L7.27518 1.32947L1.39392 5.99847L1.06788 5.99497V5.99997H0.811279V12H11.3773V5.99997H10.8933L9.93027 3.20047L8.87468 3.55797ZM9.83015 5.99997H4.78461L8.5426 4.72697L9.30839 4.48347L9.83015 5.99997ZM7.88046 3.89497L4.00122 5.20897L7.07342 2.76997L7.88046 3.89497ZM1.81757 10.0845V7.91447C2.02984 7.83972 2.22265 7.71883 2.38194 7.56062C2.54123 7.40241 2.66299 7.21087 2.73832 6.99997H9.45027C9.52558 7.21095 9.64731 7.40257 9.8066 7.56086C9.96589 7.71916 10.1587 7.84013 10.371 7.91497V10.085C10.1587 10.1598 9.96589 10.2808 9.8066 10.4391C9.64731 10.5974 9.52558 10.789 9.45027 11H2.73933C2.66395 10.7888 2.54208 10.597 2.38261 10.4387C2.22314 10.2803 2.0301 10.1593 1.81757 10.0845Z"
                                                                fill="#42FF00" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_128_11">
                                                                <rect width="12.0755" height="12" fill="white"
                                                                    transform="translate(0.0565186)" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    <h2
                                                        class='font-bold text-sm my-auto text-white text-center rounded-xl'>
                                                        {{ number_format($menu->price, 0, ',', '.') }}
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('layout.script')
    @include('sweetalert::alert')
</body>

</html>
