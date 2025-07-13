@extends('layouts.app', ['metaTitle' => 'Aktivitas Transaksi Buku', 'parentSection' => 'bookTransaction', 'elementName' => 'bookActivity'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('book.activity.index') }}"><span
                        class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Transaksi
                        Buku</span></a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('book.activity.index') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600 truncate"
                        aria-current="page">Aktifitas
                        Transaksi Buku</span></a>
            </div>
        </li>
    @endcomponent
    <div x-data="{ showFilter: {{ request()->input('transaction_type_id') || request()->input('date_from') || request()->input('date_to') || request()->input('semester_id') || request()->input('user_id') || request()->input('status') ? 'true' : 'false' }} }" class="col-span-full">
        @php
            $keepQuery = request()->only(['search', 'sort', 'direction']);
        @endphp
        <form action="{{ route('book.activity.index') }}">
            <div
                class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
                <div class="w-full mb-1">
                    <div class="sm:flex">
                        <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
                            <button type="button" @click="showFilter = !showFilter"
                                class ="inline-flex items-center justify-center w-1/2 px-3 border border-gray-200
                            py-2 text-sm font-medium text-center text-gray-900 rounded-lg bg-white hover:bg-gray-100 hover:text-blue-700 active:text-blue-700
                            focus:ring-4 focus:ring-gray-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700
                            dark:focus:ring-blue-800">
                                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="currentColor" class="size-6">
                                    <path fill-rule="evenodd"
                                        d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z"
                                        clip-rule="evenodd" />
                                </svg>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="showFilter" x-transition class="flex flex-wrap items-start justify-end gap-4">
                <div class="w-full min-w-[320px]">
                    <div class="flex flex-wrap gap-4 items-end bg-white p-4 max-w-full text-white">
                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="transaction_type"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="transaction_type_id">
                                <option value="" selected>Pilih Tipe</option>
                                @foreach ($transactionTypes as $id => $name)
                                    <option value="{{ $id }}" @selected(request('transaction_type_id') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="semester_id"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="semester_id">
                                <option value="" selected>Pilih Semester</option>
                                @foreach ($semesters as $id => $name)
                                    <option value="{{ $id }}" @selected(request('semester_id') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="user_id"
                                class=" block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="user_id">
                                <option value="" selected>Pilih Pengaju</option>
                                @foreach ($users as $id => $name)
                                    <option value="{{ $id }}" @selected(request('user_id') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group">
                            <!-- Label utama -->
                            <label for="datepicker-range-start"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Pengajuan
                            </label>

                            <!-- Date Range Picker -->
                            <div id="date-range-picker" date-rangepicker class="flex items-center gap-2">
                                <!-- Start Date -->
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input name="date_from" id="datepicker-range-start" type="text"
                                        class="peer ps-10 block w-full py-2.5 px-0 text-sm text-gray-700 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-500 dark:text-gray-300 dark:border-gray-600 dark:focus:border-blue-500"
                                        placeholder="Tanggal Mulai" value="{{ request('date_from') ?? '' }}"
                                        autocomplete="off" />
                                </div>

                                <!-- Separator -->
                                <span class="text-sm text-gray-500 dark:text-gray-400">s.d</span>

                                <!-- End Date -->
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input name="date_to" id="datepicker-range-end" type="text"
                                        class="peer ps-10 block w-full py-2.5 px-0 text-sm text-gray-700 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-500 dark:text-gray-300 dark:border-gray-600 dark:focus:border-blue-500"
                                        placeholder="Tanggal Akhir" value="{{ request('date_to') ?? '' }}"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="status"
                                class=" block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="status">
                                <option value="" selected>Pilih Status</option>
                                <option value="approved" @selected(request('status') == 'approved')>DI SETUJUI</option>
                                <option value="rejected" @selected(request('status') == 'rejected')>DI TOLAK</option>
                                <option value="cancelled" @selected(request('status') == 'cancelled')>DI BATALKAN</option>
                                <option value="pending" @selected(request('status') == 'pending')>MENUNGGU</option>
                            </select>
                        </div>

                        <div class="w-full flex justify-end mt-2">
                            <button type="button"
                                @click="window.location.href = '{{ route('book.activity.index', request()->only(['search', 'sort', 'direction'])) }}'"
                                class="mx-4 text-gray-900 border bg-white  focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Reset Filter
                            </button>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow">
                        <table
                            class=" min-w-max w-full text-sm text-left rtl:text-right whitespace-nowrap text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 w-[50px] text-xs text-center font-medium text-gray-500 uppercase dark:text-gray-400">
                                        No
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[50px] text-xs text-center font-medium text-gray-500 uppercase dark:text-gray-400">
                                        @sortablelink('user.name', 'Pengaju')
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[50px] text-xs text-center font-medium text-gray-500 uppercase dark:text-gray-400">
                                        @sortablelink('semester.name', 'Semester')
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        Tipe
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        @sortablelink('total_quantity', 'Jumlah Buku')
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        @sortablelink('created_at', 'Tanggal Pengajuan')
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        @sortablelink('approvedBy.name', 'Disetujui Oleh')
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        @sortablelink('approved_at', 'Tanggal Disetujui')
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        Alasan Ditolak
                                    </th>
                                    <th
                                        class="px-6 py-3 w-[300px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            @foreach ($bookTransactions as $key => $bookTransaction)
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td
                                            class="px-6 py-4 w-[50px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransactions->firstItem() + $key }}
                                        </td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransaction->user->name }}</td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransaction->semester->name }}</td>

                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransaction->transactionType->name }}
                                        </td>
                                        <td class="px-6 py-4 w-[200px] text-gray-900 dark:text-white text-center">
                                            @switch($bookTransaction->status)
                                                @case('approved')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                        <svg class="w-3 h-3 me-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        DISETUJUI
                                                    </span>
                                                @break
                                                @case('rejected')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                        <svg class="w-3 h-3 me-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        </svg>
                                                        DITOLAK
                                                    </span>
                                                @break
                                                @case('cancelled')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                        <svg class="w-3 h-3 me-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                        </svg>
                                                        DIBATALKAN
                                                    </span>
                                                @break
                                                @default
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                        <svg class="w-3 h-3 me-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                        MENUNGGU
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransaction->total_quantity }}
                                        </td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ Illuminate\Support\Carbon::parse($bookTransaction->created_at)->locale('id')->translatedFormat('d F Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransaction->approvedBy ? $bookTransaction->approvedBy->name : '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransaction->approved_at ? Illuminate\Support\Carbon::parse($bookTransaction->created_at)->locale('id')->translatedFormat('d F Y') : '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                            {{ $bookTransaction->rejection_reason ?? '-' }}</td>
                                        <td
                                            class="px-6 py-4 w-[300px] text-gray-900 font-medium dark:text-white text-center">
                                            <div class="inline-flex items-center gap-2">
                                                <button type="button"
                                                    @click="window.location.href = '{{ route('book.activity.detail', $bookTransaction->id) }}'"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                        {{ $bookTransactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
