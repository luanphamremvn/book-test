@extends('layouts.master-layout')
@section('content')
    {{ Breadcrumbs::render('book') }}
    <div class="w-full flex justify-end mb-8 text-gray-700">
        <form class="lg:w-[55%] w-full mx-aut">

            <div class="flex flex-col sm:flex-row gap-2 items-baseline">
                <div class="flex flex-col sm:w-auto w-full">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input datepicker id="default-datepicker" type="text" name="published_at"
                            value="{{ old('published_at', isset($filters['published_at']) ? $filters['published_at'] : '') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 lg:min-w-[150px]"
                            placeholder="Ngày xuất bản">
                    </div>
                    <div>
                        @error('published_at')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="w-full flex flex-row h-auto">
                    <div class="flex flex-row w-full">
                        <button id="dropdown-button" data-dropdown-toggle="dropdown"
                            class="shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 "
                            type="button">
                            Thể Loại
                            <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <div id="dropdown"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 ">
                            <ul class="py-2 text-sm text-gray-700 " aria-labelledby="dropdown-button">
                                @foreach ($categories as $category)
                                    <li>
                                        <button type="button" for="categories"
                                            class="inline-flex w-full items-center px-4 py-2 hover:bg-gray-100 :hover:bg-gray-600">
                                            <input name="categories[]" type="checkbox" value="{{ $category->id }}"
                                                class="w-4 h-4 mr-1.5 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500"
                                                @checked(in_array($category->id, old('categories[]', isset($filters['categories']) ? $filters['categories'] : [])))>
                                            <span>{{ $category->name }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="relative w-full">
                            <input type="search" id="search-dropdown" name="keyword" value="{{ old('keyword', isset($filters['keyword']) ? $filters['keyword'] : '') }}"
                                class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500  "
                                placeholder="Tên sách, Tác giả, Mô tả ..." />
                            <button type="submit"
                                class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                                <span class="sr-only cursor-pointer">Search</span>
                            </button>
                        </div>
                        <div>
                            @error('categories')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            @error('categories')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <a href="{{ route('books.create') }}"
                    class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-3 text-center min-w-1 sm:min-w-30 sm:w-auto w-30 ml-auto">
                    Thêm Sách
                </a>
            </div>
        </form>
    </div>
    <div class="relative overflow-x-auto">
        <table class="sm:w-full w-lg text-left rtl:text-right text-gray-500 overflow-x-auto">
            <thead class="text-gray-700 uppercase bg-gray-200">
                <tr>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Bìa sách
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Tên Sách
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Thể Loại
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Tác Giả
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Ngày xuất bản
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Mô tả
                    </th>
                    <th scope="col" class="lg:px-6 lg:py-3 text-sm">
                        Thao Tác
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($data instanceof Illuminate\Pagination\LengthAwarePaginator && count($data) > 0)
                    @foreach ($data as $book)
                        <tr class="bg-white border-b  border-gray-200">
                            <th scope="row" class="px-4 py-4">
                                <img src="{{ $book->imageUrl }}" class="sm:w-40 sm:h-28 max-w-32 h-20" />
                            </th>
                            <th class="px-6 py-4">
                                {{ $book->name }}
                            </th>
                            <td class="px-6 py-4 max-w-[200px]">
                                {{ $book->categoriesName }}
                            </td>
                            <td class="px-3 py-4">
                                {{ $book->author }}
                            </td>
                            <td class="px-3 py-4">
                                {{ $book->published_at }}
                            </td>
                            <td class="px-3 py-4 lg:max-w-3xs">
                                {{ $book->shortDescription }}
                            </td>
                            <td class="px-3 py-4 min-w-48">
                                <a type="button" href="{{ route('books.show', ['book' => $item->id]) }}"
                                    class="border-[0.5px] p-2 rounded-sm cursor-pointer text-white bg-blue-500 min-w-[60px] mr-1">
                                    Chỉnh sửa
                                </a>
                                <a href="#" onClick="deleteBook({{ $book->id }})" type="button"
                                    data-modal-target="delete-book-modal" data-modal-toggle="delete-book-modal"
                                    class="border-[0.5px] p-2 rounded-sm cursor-pointer text-white bg-red-500 min-w-[60px]">
                                    Xoá
                                </a>
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
        @if ($books instanceof LengthAwarePaginator)
            <div class="mt-3">
                {!! $books->links() !!}
            </div>
        @endif
    </div>


    <!-- delete book -->
    <div id="delete-book-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <form method="POST" action="{{ route('books.destroy', ['book' => 1]) }}" id="delete-form"
            class="relative p-4 w-full max-w-md max-h-full">
            @csrf
            @method('DELETE')
            <input type="hidden" id="delete-book-id" name="delete-book-id" />
            <div class="relative bg-white rounded-lg shadow-sm">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                    data-modal-hide="delete-book-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Đóng</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">
                        Bạn có chắc xoá quyển sách này không
                    </h3>
                    <button data-modal-hide="delete-book-modal" type="submit"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Xác nhận
                    </button>
                    <button data-modal-hide="delete-book-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                        Đóng
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const deleteBookUrl = "{{ route('books.destroy', ['book' => '%s']) }}";
        const DLB_form = document.getElementById("delete-form")

        function deleteBook(id) {
            const deleteIdInput = document.getElementById("delete-book-id")
            deleteIdInput.value = id
            DLB_form.action = deleteBookUrl.replace('%s', id)
        }
    </script>
@endsection
