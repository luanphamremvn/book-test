<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserFilterRequest;
use App\Http\Requests\User\UserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}
    /**
     * show the list of users
     * @param UserFilterRequest $request
     * @return View
     * @throws Exception
     */
    public function index(UserFilterRequest $request): View
    {
        try {
            $filters = $request->only(['keyword']);
            $users = $this->userService->getAllUser($filters);

            return view('pages.user.index', [
                'data' => $users,
                'filters' => $filters
            ]);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_GET_ALL_USER, 'Get users error', [
                'message' => $exception->getMessage(),
                'request' => $request->all(),
            ]);

            return view('pages.user.index', [
                'users' => [],
                'filters' => $request->only(['keyword']),
            ])->with(['errorMessage' => 'Lỗi hệ thống, vui lòng thử lại sau']);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('pages.user.create');
    }

    /**
     * Summary of store
     * @param UserRequest $request
     * @return RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $data = $request->only(['name', 'password', 'username', 'email']);

            //create user
            $user = $this->userService->createUser($data);

            if ($user) {
                return redirect()->route('users.index')->with('success', 'Thêm user thành công');
            }

            return redirect()->back()->with('error', 'Tạo user thất bại! vui lòng thử lại sau');
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_CREATE_USER, 'Create user error', [
                'message' => $exception->getMessage(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Tạo user thất bại! Hệ thống đang bị lỗi');
        }
    }
}
