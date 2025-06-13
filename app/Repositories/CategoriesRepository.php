<?php

namespace App\Repositories;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;

class CategoriesRepository extends BaseRepository implements CategoryRepositoryInterface
{

    public function __construct()
    {
        $this->model = app(abstract: Category::class);
    }
}
