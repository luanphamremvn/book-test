<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllUser($filters = []): LengthAwarePaginator;

    public function loginUser(array $data): bool|null;
    public function logoutUser(): void;
}
