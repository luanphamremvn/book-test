@extends('layouts.master-layout')

@section('content')
    {{ Breadcrumbs::render('book.edit') }}
    @if (isset($bookDetail))
        <form method="post" action="{{ route('books.update', ['book' => $bookDetail->id]) }}" enctype="multipart/form-data"
            class="max-w-lg mx-auto bg-white p-6">
            @csrf
            @method('PUT')
            <p class="mb-10 text-3xl">Cập nhật sách</p>
            {{-- name --}}
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="name" id="name" value="{{ old('name', $bookDetail->name) }}"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none  focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    min="3" required />
                <label for="name"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    Tên Sách
                </label>
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            {{-- author --}}
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="author" id="author" value="{{ old('author', $bookDetail->author) }}"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    required />
                <label for="author"
                    class="peer-focus:font-medium absolute text-sm text-gray-500  duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    Tác giả
                </label>
                @error('author')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            {{-- published_at --}}
            <div class="relative z-0 w-full mb-5 group">
                <input type="date" name="published_at" id="published_at"
                    value="{{ old('published_at', $bookDetail->published_at) }}"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" " required />
                <label for="published_at"
                    class="peer-focus:font-medium absolute text-sm text-gray-500  duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600  peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    Năm xuất bản
                </label>
                @error('published_at')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            {{-- published_at --}}
            <div class="relative z-0 w-full mb-5 group">
                <label for="categories" class="peer-focus:font-medium text-xs text-gray-500  duration-300 transform">
                    Thể loại
                </label>
                <x-dropdown name="categories" :options="$categories" :selected="old('categories') ?? $bookDetail->categoryIds" />
                @error('categories')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            {{-- description --}}
            <div class="w-full mb-5 group">
                <label for="description" class="peer-focus:font-medium  text-xs text-gray-500 ">
                    Mô tả
                </label>
                <textarea id="description" name="description" rows="8"
                    class="mt-4 p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                    placeholder="Mô tả nội dung sách." required>{{ old('description', $bookDetail->description) }}
                </textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            {{-- image --}}
            <div class="w-full mb-5 group">
                <label for="image" class="ppeer-focus:font-medium text-xs text-gray-500">
                    Ảnh bìa
                </label>
                <x-upload-input name="image" class="mt-3" />
                @error('image')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="flex justify-end items-center">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Update
                </button>
            </div>
        </form>
    @endif
@endsection
