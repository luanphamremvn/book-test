<div class="sticky top-0 z-40">
    <div class="bg-gray-100 font-sans w-full mb-4">
        <div class="bg-white shadow">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    <div class="flex flex-row items-center gap-5">
                        <div class="text-center">
                            <button type="button" class="cursor-pointer" data-drawer-target="drawer-navigation"
                                data-drawer-show="drawer-navigation" aria-controls="drawer-navigation">
                                <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 17 14">
                                    <path
                                        d="M16 2H1a1 1 0 0 1 0-2h15a1 1 0 1 1 0 2Zm0 6H1a1 1 0 0 1 0-2h15a1 1 0 1 1 0 2Zm0 6H1a1 1 0 0 1 0-2h15a1 1 0 0 1 0 2Z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <h2 class="text-3xl font-bold">Quản lý Sách</h2>
                    </div>

                    <div class="hidden sm:flex sm:items-center">
                        <a href="#" class="flex flex-row items-center gap-3 text-gray-800 text-sm font-semibold hover:text-purple-600 mr-4">
                            {{-- This is a placeholder for the user profile link --}}
                            <img class="w-10 h-10 rounded-full" src="{{asset('assets/images/user.png')}}" alt="user profile">
                            {{ auth()->user()->name ?? 'User' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="drawer-navigation"
        class="fixed top-0 left-0 z-40 w-81 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white "
        tabindex="-1" aria-labelledby="drawer-navigation-label">
        <h2 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase mb-5">
            Menu
        </h2>
        <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center ">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
        <div class="py-4 overflow-y-auto">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('books.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 grou">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Quản lý sách</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 grou">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 18">
                            <path
                                d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Quản lý user</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('auth.logout')}}" class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-gray-100 group">
                        <svg class="shrink-0 w-5 h-5 text-gray-500 transition duration-75  group-hover:text-gray-900"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
