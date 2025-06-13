<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{

    public function __construct(protected UserRepository $repository) {}

    /**
     * Summary of getAllUser
     * @param array $filters
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getAllUser(array $filters = []): LengthAwarePaginator
    {
        try {
            return $this->repository->getAllUser($filters);
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_GET_ALL_USER, 'Get all users error', [
                'filters' => $filters,
                'message' => $e->getMessage()
            ]);
            throw new Exception('Error retrieving users', $e->getCode(), $e);
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
            DB::transaction(function () use ($data, &$user) {
                $user = $this->repository->create($data);
            });

            return $user;
        } catch (Exception $e) {
            $this->logError(LOG_CREATE_USER, 'Create user error', [
                'data' => $data,
                'message' => $e->getMessage()
            ]);

            throw new Exception('Error creating user', $e->getCode(), $e);
        }
    }
}
