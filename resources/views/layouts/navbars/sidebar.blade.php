<aside id="sidebar"
    class="fixed top-0 left-0 z-20 flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width"
    aria-label="Sidebar">
    <div
        class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
            <div class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <ul class="pb-2 space-y-2">
                    <li>
                        <a href="/"
                            class="group flex items-center p-2 text-base font-normal rounded-lg transition duration-75 {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-6 h-6 {{ request()->routeIs('dashboard') ? 'text-gray-900 dark:text-white' : 'text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' }}"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d=" M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001
                                1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0
                                001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            <span class="ml-3" sidebar-toggle-item="">Dashboard</span>
                        </a>
                    </li>
                    @role('1', '2')
                        @php
                            $isBookActive = $parentSection === 'book';
                        @endphp
                        <li x-data="{ open: {{ $isBookActive ? 'true' : 'false' }} }">
                            <button type="button" @click="open = !open"
                                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                aria-controls="dropdown-buku" data-collapse-toggle="dropdown-buku">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6 group-hover:text-gray-900 {{ $isBookActive ? 'text-gray-900' : 'text-gray-500' }}">
                                    <path
                                        d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                                </svg>

                                <span class="flex-1 ml-3 text-left whitespace-nowrap">Buku</span>
                                <svg class="w-6 h-6 transition-transform duration-200 transform"
                                    :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <ul x-show="open" x-transition class="py-2 space-y-2">
                                <li>
                                    <a href="{{ route('category.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'category' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Kategori
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('curriculum.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                                         {{ $elementName === 'curriculum' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Kurikulum
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('education.level.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75    
                                        {{ $elementName === 'educationLevel' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Tingkat Pendidikan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('books.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75    
                                        {{ $elementName === 'books' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Daftar Buku
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @php
                            $isBookTransactionActive = $parentSection === 'bookTransaction';
                        @endphp
                        <li x-data="{ open: {{ $isBookTransactionActive ? 'true' : 'false' }} }">
                            <button type="button" @click="open = !open"
                                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                aria-controls="dropdown-book-transaction" data-collapse-toggle="dropdown-book-transaction">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6 group-hover:text-gray-900 {{ $isBookTransactionActive ? 'text-gray-900' : 'text-gray-500' }}">
                                    <path
                                        d="M12.378 1.602a.75.75 0 0 0-.756 0L3 6.632l9 5.25 9-5.25-8.622-5.03ZM21.75 7.93l-9 5.25v9l8.628-5.032a.75.75 0 0 0 .372-.648V7.93ZM11.25 22.18v-9l-9-5.25v8.57a.75.75 0 0 0 .372.648l8.628 5.033Z" />
                                </svg>


                                <span class="flex-1 ml-3 text-left whitespace-nowrap">Transaksi Buku</span>
                                <svg class="w-6 h-6 transition-transform duration-200 transform"
                                    :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <ul x-show="open" x-transition class="py-2 space-y-2">
                                <li>
                                    <a href="{{ route('semester.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'semester' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Semester
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('category.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'transactionType' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Tipe Transaksi
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endrole

                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                            aria-controls="dropdown-crud" data-collapse-toggle="dropdown-crud">
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M.99 5.24A2.25 2.25 0 013.25 3h13.5A2.25 2.25 0 0119 5.25l.01 9.5A2.25 2.25 0 0116.76 17H3.26A2.267 2.267 0 011 14.74l-.01-9.5zm8.26 9.52v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75v.615c0 .414.336.75.75.75h5.373a.75.75 0 00.627-.74zm1.5 0a.75.75 0 00.627.74h5.373a.75.75 0 00.75-.75v-.615a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75v.625zm6.75-3.63v-.625a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75v.625c0 .414.336.75.75.75h5.25a.75.75 0 00.75-.75zm-8.25 0v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75v.625c0 .414.336.75.75.75H8.5a.75.75 0 00.75-.75zM17.5 7.5v-.625a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75V7.5c0 .414.336.75.75.75h5.25a.75.75 0 00.75-.75zm-8.25 0v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75V7.5c0 .414.336.75.75.75H8.5a.75.75 0 00.75-.75z">
                                </path>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item="">CRUD</span>
                            <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-crud" class="space-y-2 py-2 hidden ">
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/crud/products/"
                                    class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700 ">Products</a>
                            </li>
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/crud/users/"
                                    class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700 ">Users</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="https://flowbite-admin-dashboard.vercel.app/settings/"
                            class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700 ">
                            <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M8.34 1.804A1 1 0 019.32 1h1.36a1 1 0 01.98.804l.295 1.473c.497.144.971.342 1.416.587l1.25-.834a1 1 0 011.262.125l.962.962a1 1 0 01.125 1.262l-.834 1.25c.245.445.443.919.587 1.416l1.473.294a1 1 0 01.804.98v1.361a1 1 0 01-.804.98l-1.473.295a6.95 6.95 0 01-.587 1.416l.834 1.25a1 1 0 01-.125 1.262l-.962.962a1 1 0 01-1.262.125l-1.25-.834a6.953 6.953 0 01-1.416.587l-.294 1.473a1 1 0 01-.98.804H9.32a1 1 0 01-.98-.804l-.295-1.473a6.957 6.957 0 01-1.416-.587l-1.25.834a1 1 0 01-1.262-.125l-.962-.962a1 1 0 01-.125-1.262l.834-1.25a6.957 6.957 0 01-.587-1.416l-1.473-.294A1 1 0 011 10.68V9.32a1 1 0 01.804-.98l1.473-.295c.144-.497.342-.971.587-1.416l-.834-1.25a1 1 0 01.125-1.262l.962-.962A1 1 0 015.38 3.03l1.25.834a6.957 6.957 0 011.416-.587l.294-1.473zM13 10a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                            <span class="ml-3" sidebar-toggle-item="">Settings</span>
                        </a>
                    </li>
                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                            aria-controls="dropdown-pages" data-collapse-toggle="dropdown-pages">
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item="">Pages</span>
                            <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-pages" class="hidden py-2 space-y-2">
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/pages/pricing/"
                                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Pricing</a>
                            </li>
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/pages/maintenance/"
                                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Maintenance</a>
                            </li>
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/pages/404/"
                                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">404
                                    not found</a>
                            </li>
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/pages/500/"
                                    class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">500
                                    server error</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                            aria-controls="dropdown-playground" data-collapse-toggle="dropdown-playground">
                            <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M6.672 1.911a1 1 0 10-1.932.518l.259.966a1 1 0 001.932-.518l-.26-.966zM2.429 4.74a1 1 0 10-.517 1.932l.966.259a1 1 0 00.517-1.932l-.966-.26zm8.814-.569a1 1 0 00-1.415-1.414l-.707.707a1 1 0 101.415 1.415l.707-.708zm-7.071 7.072l.707-.707A1 1 0 003.465 9.12l-.708.707a1 1 0 001.415 1.415zm3.2-5.171a1 1 0 00-1.3 1.3l4 10a1 1 0 001.823.075l1.38-2.759 3.018 3.02a1 1 0 001.414-1.415l-3.019-3.02 2.76-1.379a1 1 0 00-.076-1.822l-10-4z">
                                </path>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap"
                                sidebar-toggle-item="">Playground</span>
                            <svg sidebar-toggle-item="" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-playground" class="space-y-2 py-2 hidden ">
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/playground/stacked/"
                                    class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700 ">Stacked</a>
                            </li>
                            <li>
                                <a href="https://flowbite-admin-dashboard.vercel.app/playground/sidebar/"
                                    class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700 ">Sidebar</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="pt-2 space-y-2">
                    <li>
                        <a href="{{ route('user.edit') }}"
                            class="group flex items-center p-2 text-base font-normal rounded-lg transition duration-75 {{ request()->routeIs('user.edit') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 {{ request()->routeIs('user.edit') ? 'text-gray-900 dark:text-white' : 'text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' }}">
                                <path fill-rule="evenodd"
                                    d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3">Profile</span>
                        </a>
                    </li>

                    @role('1')
                        @php
                            $isUserManajemenActive = $parentSection === 'userManagement';
                        @endphp
                        <li x-data="{ open: {{ $isUserManajemenActive ? 'true' : 'false' }} }">
                            <button type="button" @click="open = !open"
                                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                aria-controls="dropdown-user-manajemen" data-collapse-toggle="dropdown-user-manajemen">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6 group-hover:text-gray-900 {{ $isUserManajemenActive ? 'text-gray-900' : 'text-gray-500' }}">
                                    <path fill-rule="evenodd"
                                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                        clip-rule="evenodd" />
                                </svg>

                                <span class="flex-1 ml-3 text-left whitespace-nowrap">User Manajemen</span>

                                <svg class="w-6 h-6 transition-transform duration-200 transform"
                                    :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <ul x-show="open" x-transition class="py-2 space-y-2">
                                <li>
                                    <a href="{{ route('user.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'user' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Daftar User
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('invite.create') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'addUser' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Tambah User
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.role') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'role' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Role
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endrole

                </ul>
            </div>
        </div>
    </div>
</aside>

<div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>
