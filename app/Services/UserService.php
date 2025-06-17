<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
    }

    /**
     * Get all users with pagination and filters
     *
     * @param array $filters
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getAllUser(array $filters = []): LengthAwarePaginator
    {
        try {
            return $this->repository->getAllUser($filters);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_GET_ALL_USER, 'Get all users error', [
                'filters' => $filters,
                'message' => $exception->getMessage()
            ]);

            // Throw a new exception with a detailed message
            throw new Exception('Error retrieving users', $exception->getCode(), $exception);
        }
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return Model|null
     * @throws Exception
     */
    public function createUser(array $data): Model|null
    {
        try {
            $user = null;

            // Validate the data before creating the user
            DB::transaction(function () use ($data, &$user) {
                $user = $this->repository->create($data);
            });

            return $user;
        } catch (Exception $exception) {
            $this->logError(LOG_CREATE_USER, 'Create user error', [
                'data' => $data,
                'message' => $exception->getMessage()
            ]);

            throw new Exception('Error creating user', $exception->getCode(), $exception);
        }
    }

    /**
     * Login user with given credentials
     *
     * @param array $credentials
     * @return bool|null
     * @throws Exception
     */
    public function loginUser(array $credentials): bool|null
    {
        try {
            return Auth::attempt($credentials);
        } catch (Exception $exception) {
            $this->logError(LOG_USER_LOGIN, 'Login user error', [
                'credentials' => $credentials,
                'message' => $exception->getMessage()
            ]);

            throw new Exception('Error logging in user', $exception->getCode(), $exception);
        }
    }
    /**
     * Logout the currently authenticated user
     *
     * @return void
     * @throws Exception
     */
    public function logoutUser(): void
    {
        try {
            Auth::logout();
        } catch (Exception $exception) {
            $this->logError(LOG_USER_LOGOUT, 'Logout user error', [
                'message' => $exception->getMessage()
            ]);

            throw $exception;
        }
    }

    /**
     * Check if the user is authenticated
     *
     * @return bool
     * @throws Exception
     */
    public function authCheck(): bool
    {
        try {
            return Auth::check();
        } catch (Exception $exception) {
            $this->logError(LOG_USER_AUTH, 'Auth check error', [
                'message' => $exception->getMessage()
            ]);

            throw $exception;
        }
    }
}
