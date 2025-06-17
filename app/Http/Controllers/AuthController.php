<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(protected UserService $userService) {}

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
            if ($this->userService->authCheck()) {
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

            abort(500, 'Đã xảy ra lỗi hệ thống vui lòng thử lại sau hoặc liên hệ với quản lý website');
        }
    }

    /**
     * Logout the currently authenticated user
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

            abort(500, 'Đã xảy ra lỗi hệ thống vui lòng thử lại sau hoặc liên hệ với quản lý website');
        }
    }
}
