<button id="dropdownBgHoverButton" data-dropdown-toggle="dropdownBgHover"
    class="flex items-center justify-between py-2 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
    type="button">
    Vui lòng chọn Thể loại
    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
    </svg>
</button>

<!-- Dropdown menu -->
<div id="dropdownBgHover" class="z-[9999] hidden bg-white rounded-lg shadow-sm w-full">
    <ul class="p-3 space-y-1 text-sm text-gray-700 w-full" aria-labelledby="dropdownBgHoverButton">
        @foreach ($options as $option)
        <li>
            <div class="flex  items-center p-2 rounded-sm hover:bg-gray-100 ">

                <input id="checkbox-item-{{ $name . '-' . $option?->category_id }}" name="{{ $name }}[]"
                    type="checkbox" value="{{ $option?->category_id }}"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500"
                    @checked(in_array($option->id, @$selected ?? []))>

                <label for="checkbox-item-{{ $name . '-' . $option?->category_id }}"
                    class="w-full ms-2 text-sm font-medium text-gray-900 rounded-sm" ">{{ $option?->name }}</label>
                        </div>
                    </li>
 @endforeach
    </ul>
</div>
