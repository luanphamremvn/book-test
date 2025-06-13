<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Traits\LogTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $with
 */
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
    protected array $whereIns = array();

    /**
     * Array of or more where
     *
     * @var array
     */
    protected array $wheres = array();

    /**
     *
     * @var array
     */
    protected array $orderBys = array();


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
     * @return mixed
     */
    public function delete(): mixed
    {
        $this->newQuery()->setClauses();

        $result = $this->query->delete();

        $this->unsetClauses();

        return $result;
    }


    /**
     * Delete the specified model record from the database
     *
     * @param $id
     *
     * @return bool|null
     * @throws Exception
     */
    public function deleteById($id): ?bool
    {
        $this->unsetClauses();

        return $this->FindById($id)->delete();
    }


    /**
     * Get the first specified model record from the database
     *
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
     * @param $id
     *
     * @return Model
     */
    public function findById($id): Model
    {
        $this->unsetClauses();

        $this->newQuery();

        return $this->query->findOrFail($id);
    }


    /**
     * Update the specified model record in the database
     *
     * @param $id
     * @param array $data
     *
     * @return Model
     */
    public function updateById($id, array $data): Model
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
     *
     * @return $this
     */
    public function where($column, $value, $operator = '='): static
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }


    /**
     * Add a simple where in clause to the query
     *
     * @param string $column
     * @param mixed  $values
     *
     * @return $this
     */
    public function whereIn($column, mixed $values): static
    {
        $values = is_array($values) ? $values : array($values);

        $this->whereIns[] = compact('column', 'values');

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
        $this->wheres   = array();
        $this->whereIns = array();

        return $this;
    }

    /**
     * @param array $columns
     * @return int
     */
    public function count($columns = []): int
    {
        $this->unsetClauses();

        $this->newQuery();

        foreach ($columns as $column => $value) {
            $this->query->where($column, $value);
        }

        return $this->query->count();
    }
}
