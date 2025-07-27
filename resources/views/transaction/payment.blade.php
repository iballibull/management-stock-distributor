@extends('layouts.app', ['metaTitle' => 'Pembayaran', 'parentSection' => 'transaction', 'elementName' => 'Pembayaran'])

@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('payment.index') }}"><span
                        class="ml-1 text-gray-700 md:ml-2 dark:text-gray-500 hover:text-blue-600"
                        aria-current="page">Transaksi</span></a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('payment.index') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600"
                        aria-current="page">Pembayaran</span></a>
            </div>
        </li>
    @endcomponent
    <div class="col-span-full mb-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($totalPayment ?? 0, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Pembayaran</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($transactions->count()) }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($totalPaid ?? 0, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Bayar</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($totalRemainingPaid ?? 0, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Sisa Pembayaran</div>
            </div>
        </div>
    </div>
    <div x-data="{ showFilter: {{ request()->input('user_id') || request()->input('transaction_type') || request()->input('status') ? 'true' : 'false' }} }" class="col-span-full">
        @php
            $keepQuery = request()->only(['sort', 'direction']);
        @endphp
        <form action="{{ route('payment.index') }}">
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
                        @if ($roleId == 2)
                            <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                                <select type="" id="user_id"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                    placeholder="" name="user_id">
                                    <option value="" selected>Semua User</option>
                                    @foreach ($users as $id => $name)
                                        <option value="{{ $id }}" @selected(request('user_id') == $id)>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="category"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="transaction_type">
                                <option value="" selected>Semua Tipe</option>
                                @foreach ($transactionTypes as $id => $name)
                                    <option value="{{ $id }}" @selected(request('transaction_type') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative z-0 basis-full sm:basis-1/2 lg:basis-1/4 flex-1 group md:max-w-[140]">
                            <select type="" id="category"
                                class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
                                placeholder="" name="status">
                                <option value="" selected>Semua Status</option>
                                @foreach ($status as $id => $name)
                                    <option value="{{ $id }}" @selected(request('status') == $id)>{{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-full flex justify-end mt-2">
                            <button type="button"
                                @click="window.location.href = '{{ route('payment.index', request()->only(['sort', 'direction'])) }}'"
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
            <div x-data="tableSort()">
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
                                            Nama
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[50px] text-xs text-center font-medium text-gray-500 uppercase dark:text-gray-400">
                                            Tipe
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('remaining_amount', 'Total Sisa Pembayaran')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('amount_paid', 'Total Bayar')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[200px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            @sortablelink('total_amount', 'Total Pembayaran')
                                        </th>
                                        <th
                                            class="px-6 py-3 w-[50px] text-xs font-medium text-gray-500 uppercase text-center dark:text-gray-400">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach ($transactions as $key => $transaction)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td
                                                class="px-6 py-4 w-[50px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ $transactions->firstItem() + $key }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[50px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ $transaction->user->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                @php
                                                    $transactionType =
                                                        $transaction->bookTransaction->transactionType->name ??
                                                        'Unknown';
                                                    $badgeClass = match ($transactionType) {
                                                        'PENGAMBILAN'
                                                            => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                        'MUTASI'
                                                            => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                        'RETUR'
                                                            => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                        default
                                                            => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                                    };
                                                @endphp
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-medium {{ $badgeClass }} rounded-full">
                                                    {{ $transactionType }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                @php
                                                    $status = match ($transaction->status) {
                                                        'UNPAID' => 'BELUM DIBAYAR',
                                                        'PAID' => 'LUNAS',
                                                        default => 'DI CICIL',
                                                    };
                                                @endphp
                                                <span
                                                    class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-left">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ 'Rp ' . number_format($transaction->remaining_amount, 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ 'Rp ' . number_format($transaction->amount_paid, 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-6 py-4 w-[200px] text-gray-900 font-medium dark:text-white text-center">
                                                {{ 'Rp ' . number_format($transaction->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 w-[50px] text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('payment.detail', ['transactionId' => $transaction->id]) }}"
                                                        class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                        Lihat Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Delete categori Modal -->
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $transactions->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
