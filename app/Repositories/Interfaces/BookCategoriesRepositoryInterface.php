<?php

namespace App\Repositories\Interfaces;

interface BookCategoriesRepositoryInterface extends BaseRepositoryInterface
{
    public function deleteByBookId(int $bookId, array $categoryIds = []): bool;
    public function createByBookId(int $bookId, array $categoryIds = []): void;
}
