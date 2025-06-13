<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'name' =>'string|required|min:1|max:255',
            'username' => 'string|required|unique:users|min:5|max:255|regex:/^\S*$/|max:255',
            'email' => 'unique:users|email|min:6|max:255',
            'password' => 'min:8|regex:/[^\w]/|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:8'
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.min' => 'tên sách ít nhất 1 ký tự',
            'name.max' => 'tên sách tối đa 255 ký tự',
            'username.unique' => 'username đã tồn tại',
            'username.regex' => 'username không được chứa khoảng trắng',
            'username.min' => 'username ít nhất 5 ký tự',
            'username.max' => 'username tối đa 255 ký tự',
            'email.max' => 'tên tác giả tối đa 255 ký tự',
            'email.min' => 'mô tả ít nhất 6 ký tự',
            'email.unique' => 'email đã tồn tại',
            'password.min' => 'mật khẩu ít nhất 8 ký tự',
            'password.required_with' => 'vui lòng nhập mật khẩu',
            'password.same' => 'mật khẩu không khớp',
            'password_confirmation.min' => 'mật khẩu xác nhận ít nhất 8 ký tự',
            'password_confirmation.required_with' => 'vui lòng nhập mật khẩu xác nhận',
            'password_confirmation.same' => 'mật khẩu xác nhận không khớp',
            'password.regex' => 'mật khẩu phải chứa ít nhất một ký tự đặc biệt',
            'password_confirmation.min' => 'mật khẩu xác nhận ít nhất 8 ký tự',
            'password_confirmation.same' => 'mật khẩu xác nhận không khớp',
            'password_confirmation.required_with' => 'vui lòng nhập mật khẩu xác nhận',
        ];
    }
}
