<?php

namespace App\Services;

use App\Traits\LogTrait;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Exception;

abstract class BaseService
{
    use LogTrait;

    /**
     * log prefix
     *
     * @var string
     */
    protected string $logPrefix = 'Service';

    /**
     * The repository instance.
     *
     * @var BaseRepositoryInterface
     */
    protected BaseRepositoryInterface $repository;

    /**
     * BaseService constructor.
     *
     * @param BaseRepositoryInterface $repository
     */
    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all records.
     *
     * @return Collection<Model>
     */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Create a new record.
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->repository->create($attributes);
    }

    /**
     * Delete records by conditions.
     *
     * @return int|bool
     */
    public function delete(): int|bool
    {
        return $this->repository->delete();
    }

    /**
     * Delete a record by ID.
     *
     * @param int|string $id
     * @return bool|null
     * @throws Exception
     */
    public function deleteById(int|string $id): ?bool
    {
        return $this->repository->deleteById($id);
    }

    /**
     * Get the first record matching the conditions.
     *
     * @return Model
     */
    public function first(): Model
    {
        return $this->repository->first();
    }

    /**
     * Get records matching the conditions.
     *
     * @return Collection<Model>
     */
    public function get(): Collection
    {
        return $this->repository->get();
    }

    /**
     * Find a record by ID.
     *
     * @param int|string $id
     * @return Model
     */
    public function findById(int|string $id): Model
    {
        return $this->repository->findById($id);
    }

    /**
     * Update a record by ID.
     *
     * @param int|string $id
     * @param array $attributes
     * @return Model
     */
    public function updateById(int|string $id, array $attributes): Model
    {
        return $this->repository->updateById($id, $attributes);
    }

    /**
     * Add a where clause.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     * @return static
     */
    public function where(string $column, string $value, string $operator = '='): static
    {
        $this->repository->where($column, $value, $operator);

        return $this;
    }

    /**
     * Add a whereIn clause.
     *
     * @param string $column
     * @param array $values
     * @return static
     */
    public function whereIn(string $column, array $values): static
    {
        $this->repository->whereIn($column, $values);

        return $this;
    }

    /**
     * Add an orderBy clause.
     *
     * @param string $column
     * @param string $direction
     * @return static
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->repository->orderBy($column, $direction);

        return $this;
    }

    /**
     * Count records matching the given columns and clauses.
     *
     * @param array $columns
     * @return int
     */
    public function count(array $columns = []): int
    {
        return $this->repository->count($columns);
    }

    /**
     * Paginate the results.
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = PAGINATION_PER_PAGE, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }
}

