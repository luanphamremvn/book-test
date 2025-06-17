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
     * @param array $filters
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getAllUser(array $filters = []): LengthAwarePaginator
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
}
