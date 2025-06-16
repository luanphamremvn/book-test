@extends('layouts.master-layout')
@section('content')
    {{ Breadcrumbs::render('user') }}
    <div class="w-full flex justify-end mb-8 text-gray-700">
        <form class="lg:w-[35%] w-full mx-aut">
            <div class="flex flex-row gap-3">
                <div class="w-full flex flex-row">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 18 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2" />
                            </svg>
                        </div>
                        <input type="text" name="q" value="{{ old('q', @$filters['q']) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5"
                            placeholder="Tìm tên, email hoặc username" required />
                    </div>
                    <button type="submit"
                        class="p-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Tìm</span>
                    </button>
                </div>
                <a href="{{ route('users.create') }}"
                    class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2 text-center min-w-1 sm:min-w-30 sm:w-auto w-30 ml-auto">
                    Thêm User
                </a>
            </div>
        </form>
    </div>
    <div class="relative overflow-x-auto">
        <table class="sm:w-full w-lg text-left rtl:text-right text-gray-500 overflow-x-auto">
            <thead class="text-gray-700 uppercase bg-gray-200">
                <tr>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Họ và Tên
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        UserName
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Email
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (count($users) > 0)
                    @foreach ($users as $user)
                        <tr class="bg-white border-b  border-gray-200">
                            <th class="px-6 py-4">
                                {{ $user->name }}
                            </th>
                            <td class="px-6 py-4 max-w-[200px]">
                                {{ $user->username }}
                            </td>
                            <td class="px-3 py-4">
                                {{ $user->email }}
                            </td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" rowspan="5" class="text-center p-5">
                            Không có dữ liệu
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        {{-- pagination links --}}
        @if ($users instanceof LengthAwarePaginator)
            <div class="mt-3">
                {!! $users->links() !!}
            </div>
        @endif
    </div>
@endsection
