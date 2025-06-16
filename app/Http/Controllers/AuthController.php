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
     * AuthController constructor.
     */
    public function __construct(protected \App\Services\UserService $userService)
    {

    }

    /**
     * Show login page
     *
     * @return Factory|Application|View|RedirectResponse
     * @throws Exception
     */
    public function login(): Factory|Application|View|RedirectResponse
    {
        try {
            // Check if the user is already authenticated
            if (Auth::check()) {
                return redirect()->route('books.index');
            }
        } catch (Exception $exception) {
            $this->logError(LOG_USER_LOGIN, 'Error login page', [
                'message' => $exception->getMessage()
            ]);
        }

        // If not authenticated, show the login page
        return view('pages.auth.login');
    }

    /**
     * Authenticate user
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        try {
            $credentials = $request->only(['username', 'password']);

            if ($this->userService->loginUser($credentials)) {
                return redirect()->route('books.index')->with('success', 'Đăng nhập thành công');
            }

            // If authentication fails, redirect back with an error message
            return redirect()->back()->withErrors([
                'errorMessage' => 'Tên tài khoản hoặc mật khẩu không chính xác!'
            ]);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_USER_LOGIN, 'Error authenticate user', [
                'message' => $exception->getMessage(),
                'data' => $request->all()
            ]);

            return redirect()->back()->withErrors([
                'errorMessage' => 'Đã xây ra lỗi hệ thống vui lòng thử lại sau hoặc liên hệ với quản lý website'
            ]);
        }
    }

    /**
     * Logout user
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function logout(): RedirectResponse
    {
        try {
            // Attempt to log out the user
            $this->userService->logoutUser();

            return redirect()->route('login')->with('success', 'Đăng xuất thành công');
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_USER_LOGOUT, 'Error logout user', [
                'message' => $exception->getMessage()
            ]);

            // Redirect back with an error message
            return redirect()->back()->withErrors([
                'errorMessage' => 'Đã xảy ra lỗi hệ thống vui lòng thử lại sau hoặc liên hệ với quản lý website'
            ]);
        }
    }
}
