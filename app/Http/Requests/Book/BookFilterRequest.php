<?php

namespace App\Http\Requests\Book;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the filter request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string|max:255',
            'published_at' => 'nullable|date|string',
            'categories' => 'nullable|array',
            'categories.*' => 'integer',
        ];
    }

    /**
     * Get custom error messages for validator failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'keyword.max' => 'Bạn chỉ có thể tìm kiếm tối đa 255 ký tự',
            'published_at.string' => 'Ngày xuất bản phải là một chuỗi hợp lệ',
            'published_at.date' => 'Ngày xuất bản phải là một ngày hợp lệ',
            'categories.array' => 'Thể loại phải là một mảng hợp lệ',
            'categories.*.integer' => 'Thể loại tìm kiếm không hợp lệ, vui lòng chọn lại thể loại',
        ];
    }
}
