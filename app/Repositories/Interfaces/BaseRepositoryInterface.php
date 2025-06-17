<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{

    public function all(): array|Collection;

    public function create(array $data): Model;

    public function delete(): int|false;

    public function deleteById(int|string $id): ?bool;

    public function first(): Model;

    public function get(): Collection;

    public function findById(int|string $id): Model;

    public function updateById(int|string $id, array $data): Model;

    public function where(string $column,string $value,string $operator = '='): static;

    public function whereIn(string $column, array $values): static;

    public function orderBy(string $column, string $direction = 'asc'): static;

    public function count($columns = []): int;

    public function paginate(int $perPage = PAGINATION_PER_PAGE, array $columns = ['*']): LengthAwarePaginator;
}
