@extends('layouts.master-layout')

@section('content')
    <form method="post" action="{{ route('auth.login') }}" class="max-w-lg mx-auto mt-3 rounded-lg bg-white p-10">
        @csrf
        {{-- title --}}
        <p class="mb-10 text-4xl text-center">Đăng nhập</p>

        @error('errorMessage')
            <span class="text-red-800 mb-5">{{ $message }}</span>
        @enderror
        {{-- username --}}
        <div class="mb-5">
            <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">Tên đăng nhập</label>
            <input type="text" id="username" name="username"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="username1" required />
            @error('username')
                <span class="text-red-600 text-sm mt-5">{{ $message }}</span>
            @enderror
        </div>
        {{-- password --}}
        <div class="mb-5">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Mật khẩu</label>
            <input type="password" id="password" name="password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                placeholder="Vui lòng nhập mật khẩu" required />
            @error('password')
                <span class="text-red-600 text-sm mt-5">{{ $message }}</span>
            @enderror
        </div>
        {{-- button login --}}
        <div class="flex flex-row justify-end">
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center ">
                Đăng nhập
            </button>
        </div>
    </form>
@endsection
