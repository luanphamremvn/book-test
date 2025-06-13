<?php

namespace App\Repositories\Interfaces;

use App\Enums\BookStatusEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use App\Models\Book;

interface BookRepositoryInterface extends BaseRepositoryInterface
{
    public function createBook(array $data);
    public function getAllBookQuery(array $filters, $status = BookStatusEnum::ACTIVE->value): LengthAwarePaginator;
    public function updateBookById(int $id, array $data): Exception|Book|null;
}
