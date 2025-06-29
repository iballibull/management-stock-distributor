@extends('layouts.app', ['metaTitle' => 'Daftar User', 'parentSection' => 'userManagement', 'elementName' => 'user'])
@section('content')
    @component('layouts.headers.breadcrumbs')
        <li>
            <div class="flex items-center">
                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <a href="{{ route('user.index') }}"><span
                        class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500 hover:text-blue-600" aria-current="page">Daftar
                        User</span></a>
            </div>
        </li>
    @endcomponent

    <div class="col-span-full">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex items-center justify-between flex-wrap md:flex-nowrap py-4 bg-white dark:bg-gray-900">
                <div class="ml-auto mx-4 flex items-center">
                    <label for="table-search" class="sr-only">Search</label>
                    <form action="" class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="text" id="table-search-users"
                            class="block ps-10 py-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Cari nama atau email" name="search" value="{{ request('search') ?? '' }}">
                    </form>
                </div>
            </div>


            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3"> No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th class="px-6 py-3" scope="row">
                                {{ $users->firstItem() + $key }}
                            </th>
                            <th scope="row"
                                class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                <img class="w-10 h-10 rounded-full"
                                    src="{{ asset('storage/' . ($user->photo ?? 'photos/default.png')) }}" alt="Jese image">
                                <div class="ps-3">
                                    <div class="text-base font-semibold">{{ $user->name }}</div>
                                    <div class="font-normal text-gray-500">{{ $user->email }}</div>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->role->name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($user->deleted_at == null)
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Aktif
                                    @else
                                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Non Aktif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" data-modal-target="edit-user-modal{{ $user->id }}"
                                    data-modal-toggle="edit-user-modal{{ $user->id }}"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                        </path>
                                        <path fill-rule="evenodd"
                                            d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Edit
                                </button>
                            </td>

                            <!-- Edit User Modal -->
                            <div class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/30 overflow-x-hidden overflow-y-auto"
                                id="edit-user-modal{{ $user->id }}">
                                <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto p-4">
                                    <!-- Modal content -->
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                        <!-- Modal header -->
                                        <div
                                            class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700 border-gray-200">
                                            <h3 class="text-xl font-semibold dark:text-white">
                                                Edit User
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                                data-modal-toggle="edit-user-modal{{ $user->id }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="p-6 ">
                                            <form action="{{ route('user.update', ['userId' => $user->id]) }}"
                                                method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="grid grid-cols-6 gap-6">
                                                    <!-- First Status Dropdown -->
                                                    <div class="col-span-6 sm:col-span-3">
                                                        <label for="status"
                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                                        <select name="status" id="status"
                                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            required>
                                                            <option value="active" @selected($user->deleted_at == null)>Aktif
                                                            </option>
                                                            <option value="inactive" @selected($user->deleted_at != null)>Non Aktif
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <!-- Role Dropdown -->
                                                    <div class="col-span-6 sm:col-span-3">
                                                        <label for="role_id"
                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                                                        <select name="role_id" id="role_id"
                                                            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            required>
                                                            <option value="1" @selected($user->role_id == 1)>Owner
                                                            </option>
                                                            <option value="2" @selected($user->role_id == 2)>Admin
                                                            </option>
                                                            <option value="3" @selected($user->role_id == 3)>Sales
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                        </div>
                                        <!-- Modal footer -->
                                        <div
                                            class="flex justify-end items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-700">
                                            <button
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                type="submit">
                                                Simpan
                                            </button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links('pagination::tailwind') }}
        </div>
    @endsection
</div>
