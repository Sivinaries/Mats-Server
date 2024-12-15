<div class="flex">
    <aside id="sidebar"
        class="font-poppins fixed inset-y-0 my-6 ml-4 w-full max-w-72 md:max-w-60 xl:max-w-64 2xl:max-w-64 z-50 rounded-3xl bg-white shadow-2xl overflow-y-scroll transform transition-transform duration-300 -translate-x-full md:translate-x-0 ease-in-out">
        <div class="p-2">
            <div class="p-8">
                <a class="text-center" href="{{ route('dashboard') }}">
                    <h1 class="font-extrabold text-4xl text-black">Mats</h1>
                </a>
            </div>
            <hr class="mx-5 shadow-2xl bg-transparent rounded-r-xl rounded-l-xl" />
            <div>
                <ul class="">
                    <li class="p-4 mx-2">
                        <a class="" href="{{ route('dashboard') }}">
                            <div class="flex space-x-4">
                                <div class="bg-black p-2 rounded-xl">
                                    <i class="fa-solid fa-house text-white"></i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Dashboard</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a class="" href="{{ route('order') }}">
                            <div class="flex space-x-4">
                                <div class="bg-black p-2 rounded-xl">
                                    <i class="fa-duotone fa-solid fa-list text-white"></i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Order</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <a class="" href="{{ route('scanner') }}">
                            <div class="flex space-x-4">
                                <div class="bg-black p-2 rounded-xl">
                                    <i class="fa-solid fa-qrcode text-white"></i>
                                </div>
                                <div class="my-auto">
                                    <h1 class="text-gray-500 hover:text-black text-base font-normal">Scanner</h1>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li class="p-4 mx-2">
                        <div class="flex space-x-4">
                            <div class="bg-black p-2 rounded-xl">
                                <i class="fa-sharp-duotone fa-solid fa-gear text-white"></i>
                            </div>
                            <div class="my-auto">
                                <h1 class="text-black text-base font-normal">Manage</h1>
                            </div>
                        </div>
                    </li>
                    <hr class="mx-5 shadow-2xl bg-transparent rounded-r-xl rounded-l-xl" />
                    <li class="p-4 mx-2">
                        <div class="ml-16 md:ml-14">
                            <a href="{{ route('category') }}">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Category</h1>
                            </a>
                        </div>
                    </li>
                    <li class="p-4 mx-2">
                        <div class="ml-16 md:ml-14">
                            <a href="{{ route('product') }}">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Product</h1>
                            </a>
                        </div>
                    </li>
                    <li class="p-4 mx-2">
                        <div class="ml-16 md:ml-14">
                            <a href="{{ route('inventory') }}">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Inventory</h1>
                            </a>
                        </div>
                    </li>
                    <li class="p-4 mx-2">
                        <div class="ml-16 md:ml-14">
                            <a href="{{ route('settlement') }}">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Settlement</h1>
                            </a>
                        </div>
                    </li>
                    <li class="p-4 mx-2">
                        <div class="ml-16 md:ml-14">
                            <a href="{{ route('expense') }}">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">Expense</h1>
                            </a>
                        </div>
                    </li>
                    <li class="p-4 mx-2">
                        <div class="ml-16 md:ml-14">
                            <a href="{{ route('user') }}">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">User</h1>
                            </a>
                        </div>
                    </li>
                    <li class="p-4 mx-2">
                        <div class="ml-16 md:ml-14">
                            <a href="{{ route('history') }}">
                                <h1 class="text-gray-500 hover:text-black text-base font-normal">History</h1>
                            </a>
                        </div>
                    </li>
                    <hr class="mx-5 shadow-2xl bg-transparent rounded-r-xl rounded-l-xl" />
                    <li class="p-4">
                        <form class="flex space-x-4" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <div class="bg-black p-2 rounded-xl">
                                <i class="fa-solid fa-right-from-bracket text-white"></i>
                            </div>
                            <button class="text-gray-500 hover:text-black text-base font-normal" type="submit">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</div>
