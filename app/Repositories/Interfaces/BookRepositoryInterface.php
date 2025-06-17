<?php

namespace App\Repositories\Interfaces;

use App\Enums\BookStatusEnum;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

interface BookRepositoryInterface extends BaseRepositoryInterface
{
    public function createBook(array $data): Model|string;
    public function getAllBookQuery(array $filters,string $status = BookStatusEnum::ACTIVE->value): LengthAwarePaginator;
    public function updateBookById(int $id, array $data): Exception|Book|null;
}
