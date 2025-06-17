<?php

namespace App\Http\Requests\Book;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'string|required|min:3|max:255',
            'author' => 'string|required|min:3|max:50',
            'published_at' => 'date|required',
            'categories' => 'array|required|min:1',
            'categories.*' => 'exists:categories,category_id',
            'description' => 'string|required|min:20|max:2500',
            'image' => 'file|required|mimes:jpg,png,svg,jpeg'
        ];

        if ($this->routeIs('books.update')) {
            $rules['image'] = 'nullable|file|mimes:jpg,png,svg,jpeg';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.min' => 'tên sách ít nhất 3 ký tự',
            'name.max' => 'tên sách tối đa 255 ký tự',
            'author.min' => 'tên tác giả ít nhất 3 ký tự',
            'author.max' => 'tên tác giả tối đa 50 ký tự',
            'description.min' => 'mô tả ít nhất 20 ký tự',
            'description.max' => 'Mô tả không được vượt quá 2500 ký tự.',
            'categories.required' => 'vui lòng chọn thể loại',
            'categories.exits' => 'có 1 thể loại bạn vừa chọn đã bị xoá hoặc không tồn tại',
            'published_at.required' => 'vui lòng chọn ngày xuất bản',
            'image.required' => 'vui lòng thêm ảnh bìa',
            'image.mimes' => 'chỉ có thể upload ảnh, ảnh có định dạng hỗ trợ JPG,JPEG,PNG OR SVG'
        ];
    }
}
