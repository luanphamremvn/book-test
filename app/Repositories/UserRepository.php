<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct()
    {
        $this->model = app(User::class);
    }


    /**
     * Get all users with optional filters
     *
     * @param mixed $filters
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getAllUser($filters = []): LengthAwarePaginator
    {
        try {
            // Apply filters if provided
            return $this->model
                ->query()
                ->when(isset($filters['keyword']), function ($query) use ($filters) {
                    return $query->search($filters['keyword']);
                })
                ->paginate(PAGINATION_PER_PAGE);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_GET_ALL_USER, 'Get all users error', [
                'filters' => $filters,
                'message' => $exception->getMessage()
            ]);

            throw $exception;
        }
    }

    /**
     * Login user with given credentials
     *
     * @param array $data
     * @return bool|null
     * @throws Exception
     */
    public function loginUser(array $data): bool|null
    {
        try {
            // Attempt to authenticate the user
            return $this->model->attempt($data);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_USER_LOGIN, 'Login user error', [
                'data' => $data,
                'message' => $exception->getMessage()
            ]);

            // Rethrow the exception to be handled by the service layer
            throw $exception;
        }
    }

    /**
     * Logout the currently authenticated user
     *
     * @throws Exception
     */
    public function logoutUser(): void
    {
        try {
            // Log out the user
            $this->model->logout();
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_USER_LOGOUT, 'Logout user error', [
                'message' => $exception->getMessage()
            ]);

            throw $exception;
        }
    }
}
