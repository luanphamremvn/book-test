<?php

namespace App\Repositories\Interfaces;

interface BookCategoriesRepositoryInterface extends BaseRepositoryInterface{

    public function deleteBookCategoriesByBookId(int $bookId,array $categoryIds = []): bool;
    public function createBookCategoriesByBookId(int $bookId, array $categoryIds = []): void;
}
