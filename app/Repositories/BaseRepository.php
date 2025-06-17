<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Traits\LogTrait;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
abstract class BaseRepository implements BaseRepositoryInterface
{
    use LogTrait;

    /**
     * The repository model
     *
     * @var Model
     */
    protected Model $model;

    /**
     * The query builder
     *
     * @var Builder
     */
    protected Builder $query;

    /**
     * log prefix
     *
     * @var string
     */
    protected string $logPrefix = 'Repository';

    /**
     * Array of one or more where in clause parameters
     *
     * @var array
     */
    protected array $whereIns = [];

    /**
     * Array of or more where
     *
     * @var array
     */
    protected array $wheres = [];

    /**
     *
     * @var array
     */
    protected array $orderBys = [];

    /**
     * Array of eager loading relations.
     *
     * @var array
     */
    protected array $with = [];

    /**
     * Get all the model records in the database
     *
     * @return Collection
     */
    public function all(): Collection
    {
        $this->newQuery();
        $models = $this->query->get();
        $this->unsetClauses();

        return $models;
    }

    /**
     * Create a new model record in the database
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model
    {
        $this->unsetClauses();

        return $this->model->query()->create($data);
    }


    /**
     * Delete one or more model records from the database
     *
     * @return int|false
     */
    public function delete(): int|false
    {
        $this->newQuery()->setClauses();
        // Prevent deleting all records if no where or whereIn is set
        if (empty($this->wheres) && empty($this->whereIns)) {
            $this->unsetClauses();
            return false;
        }

        $result = $this->query->delete();
        $this->unsetClauses();

        return $result;
    }


    /**
     * Delete the specified model record from the database
     *
     * @param int|string $id
     * @return bool|null
     * @throws Exception
     */
    public function deleteById(int|string $id): ?bool
    {
        $this->unsetClauses();

        return $this->findById($id)->delete();
    }


    /**
     * Get the first specified model record from the database
     * @return Model
     */
    public function first(): Model
    {
        $this->newQuery()->setClauses();
        $model = $this->query->firstOrFail();
        $this->unsetClauses();

        return $model;
    }


    /**
     * Get all the specified model records in the database
     *
     * @return Collection
     */
    public function get(): Collection
    {
        $this->newQuery()->setClauses();
        $models = $this->query->get();
        $this->unsetClauses();

        return $models;
    }


    /**
     * Get the specified model record from the database
     *
     * @param int|string $id
     * @return Model
     */
    public function findById(int|string $id): Model
    {
        $this->unsetClauses();
        $this->newQuery();

        return $this->query->findOrFail($id);
    }


    /**
     * Update the specified model record in the database
     *
     * @param int|string $id
     * @param array $data
     *
     * @return Model
     */
    public function updateById(int|string $id, array $data): Model
    {
        $this->unsetClauses();
        $model = $this->findById($id);
        $model->update($data);

        return $model;
    }


    /**
     * Add a simple where clause to the query
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     * @return $this
     */
    public function where(string $column,string $value,string $operator = '='): static
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }


    /**
     * Add a simple where in clause to the query
     *
     * @param string $column
     * @param array $values
     * @return $this
     */
    public function whereIn(string $column, array $values): static
    {
        $values = is_array($values) ? $values : array($values);
        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Add an order by clause to the query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * Set eager loading relations.
     *
     * @param array $relations
     * @return $this
     */
    public function with(array $relations): static
    {
        $this->with = $relations;

        return $this;
    }


    /**
     * Create a new instance of the model's query builder
     *
     * @return $this
     */
    protected function newQuery(): static
    {
        $this->query = $this->model->newQuery();

        if (!empty($this->with)) {
            $this->query->with($this->with);
        }

        return $this;
    }

    /**
     * Set clauses on the query builder
     *
     * @return $this
     */
    protected function setClauses(): static
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        return $this;
    }


    /**
     * Reset the query clause parameter arrays
     *
     * @return $this
     */
    protected function unsetClauses(): static
    {
        $this->wheres   = [];
        $this->whereIns = [];
        $this->orderBys = [];
        $this->with     = [];

        return $this;
    }

    /**
     * Count the number of records matching the given columns and clauses.
     *
     * @param array $columns
     * @return int
     */
    public function count($columns = []): int
    {
        $this->newQuery()->setClauses();

        foreach ($columns as $column => $value) {
            $this->query->where($column, $value);
        }

        $count = $this->query->count();
        $this->unsetClauses();

        return $count;
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
        $this->newQuery()->setClauses();
        $paginator = $this->query->paginate($perPage, $columns);
        $this->unsetClauses();

        return $paginator;
    }
}
