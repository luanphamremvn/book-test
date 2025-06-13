<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{

    public function all();

    public function create(array $data);

    public function delete();

    public function deleteById($id);

    public function first();

    public function get();

    public function findById($id);

    public function updateById($id, array $data);

    public function where($column, $value, $operator = '=');

    public function whereIn($column, mixed $values);

    public function count($columns = []);
}
