<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * login page
     * @return Factory|Application|View|RedirectResponse
     */
    public function login(): Factory|Application|View|RedirectResponse
    {
        try {
            // Check if the user is already authenticated
            if (Auth::check()) {
                return redirect()->route('books.index');
            }
        } catch (Exception $e) {
            $this->logError(LOG_USER_LOGIN, 'Error login page', [
                'message' => $e->getMessage()
            ]);
        }
        // If not authenticated, show the login page
        return view('pages.auth.login');
    }

    /**
     * check login information
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        try {
            $certificate = $request->only(['username', 'password']);
            if (Auth::attempt($certificate)) {
                return redirect()->route('books.index')->with('success', 'Đăng nhập thành công');
            } else {
                return redirect()->back()->withErrors([
                    'errorMessage' => 'Tên tài khoản hoặc mật khẩu không chính xác!'
                ]);
            }
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_USER_LOGIN, 'Error authenticate user', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);
            return redirect()->back()->withErrors([
                'errorMessage' => 'Đã xây ra lỗi hệ thống vui lòng thử lại sau hoặc liên hệ với quản lý website'
            ]);
        }
    }

    /**
     * logout user
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        try {
            Auth::logout();
            return redirect()->route('login')->with('success', 'Đăng xuất thành công');
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_USER_LOGOUT, 'Error logout user', [
                'message' => $e->getMessage()
            ]);
            return redirect()->back()->withErrors([
                'errorMessage' => 'Đã xảy ra lỗi hệ thống vui lòng thử lại sau hoặc liên hệ với quản lý website'
            ]);
        }
    }
}
