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
                    @endrole

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
                            @role('1', '2')
                                <li>
                                    <a href="{{ route('semester.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'semester' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Semester
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('transaction.type.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'transactionType' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Tipe Transaksi
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('book.stock.in.create') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'bookTransactionCreate' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Tambah Stok Buku
                                    </a>
                                </li>
                            @endrole
                            <li>
                                <a href="{{ route('book.activity.index') }}"
                                    class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'bookActivity' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                    Aktifitas Transaksi Buku
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('book.stock.index') }}"
                            class="group flex items-center p-2 text-base font-normal rounded-lg transition duration-75 {{ request()->routeIs('book.stock.index') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            <svg class="w-6 h-6 {{ request()->routeIs('book.stock.index') ? 'text-gray-900 dark:text-white' : 'text-gray-500 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white' }}"
                                fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                <path fill-rule="evenodd"
                                    d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-3" sidebar-toggle-item="">Stok Buku</span>
                        </a>
                    </li>
                    @php
                        $isTransaction = $parentSection === 'transaction';
                    @endphp
                    <li x-data="{ open: {{ $isTransaction ? 'true' : 'false' }} }">
                        <button type="button" @click="open = !open"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                            aria-controls="dropdown-transaction" data-collapse-toggle="dropdown-transaction">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 group-hover:text-gray-900 {{ $isTransaction ? 'text-gray-900' : 'text-gray-500' }}">
                                <path d="M4.5 3.75a3 3 0 0 0-3 3v.75h21v-.75a3 3 0 0 0-3-3h-15Z" />
                                <path fill-rule="evenodd"
                                    d="M22.5 9.75h-21v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5ZM6 13.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 0 1.5h-3A.75.75 0 0 1 6 13.5Zm.75 2.25a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5h-1.5Z"
                                    clip-rule="evenodd" />
                            </svg>


                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Transaksi</span>
                            <svg class="w-6 h-6 transition-transform duration-200 transform"
                                :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition class="py-2 space-y-2">
                            @role('1', '2')
                                <li>
                                    <a href="{{ route('transaction.omzet.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'Omzet' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Omzet</a>
                                </li>
                                <li>
                                    <a href="{{ route('transaction.revenue.index') }}"
                                        class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'Pendapatan' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                        Pendapatan</a>
                                </li>
                            @endrole
                            <li>
                                <a href="{{ route('payment.index') }}"
                                    class="flex items-center p-2 text-base rounded-lg pl-11 group transition duration-75
                {{ $elementName === 'Pembayaran' ? 'bg-gray-100' : 'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                    Pembayaran</a>
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
