<?php

namespace App\Repositories;

use App\Enums\BookStatusEnum;
use App\Repositories\Interfaces\BookCategoriesRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    public function __construct(
        protected BookCategoriesRepositoryInterface $bookCategories
    ) {
        $this->model = app(Book::class);
    }

    /**
     * Create a new book and its categories.
     *
     * @param array $data
     * @return Model|string|Exception
     * @throws Exception
     */
    public function createBook(array $data): Model|string|Exception
    {
        DB::beginTransaction();

        try {
            // create book
            $book = $this->create($data);

            //create book categories
            if ($book instanceof Book) {
                $categories = isset($data["categories"]) ? $data["categories"] : [];
                $this->bookCategories->createByBookId($book->id, $categories);
            }

            // commit transaction
            DB::commit();

            return $book;
        } catch (Exception $exception) {
            // rollback transaction
            DB::rollBack();

            // log error message
            $this->logError(LOG_CREATE_BOOK, 'Create book error', [
                'message' => $exception->getMessage(),
                'data' => $data
            ]);

            throw $exception;
        }
    }

    /**
     *  Get all books with optional filters and status.
     *
     * @param array $filters
     * @param mixed $status
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getAllBookQuery(array $filters = [], $status = BookStatusEnum::ACTIVE->value): LengthAwarePaginator
    {
        try {
            return $this->model
                ->with(['categories'])
                ->when(
                    isset($filters['categories']) && is_array($filters['categories']) && count($filters['categories']) > 0,
                    function ($query) use ($filters) {
                        return $query->whereHas('categories', function ($query) use ($filters) {
                            return $query->whereIn('categories.id', $filters['categories']);
                        });
                    }
                )
                ->when(
                    isset($filters['keyword']) && is_string($filters['keyword']) && $filters['keyword'] !== '',
                    function ($query) use ($filters) {
                        return $query->search($filters['keyword']);
                    }
                )
                ->when(
                    isset($filters['published_at']) && $this->isValidDate($filters['published_at']),
                    function ($query) use ($filters) {
                        return $query->whereDate('published_at', Carbon::parse($filters['published_at']));
                    }
                )
                ->where('status', $status)
                ->paginate(PAGINATION_PER_PAGE);
        } catch (Exception $exception) {
            // log error message
            $this->logError(LOG_GET_ALL_BOOK, 'Get all books error', [
                'message' => $exception->getMessage(),
                'filters' => $filters
            ]);

            throw $exception;
        }
    }

    /**
     *  Update a book and its categories by book ID.
     *
     * @param int $id
     * @param array $data
     * @return Exception|Book|null
     * @throws Exception
     */
    public function updateBookById(int $id, array $data): Exception|Book|null
    {
        try {
            // update book if exists
            $book = $this->updateById($id, $data);

            //update book categories
            if ($book instanceof Book) {
                $categories = isset($data["categories"]) ? $data["categories"] : [];
                // delete old categories and create new categories
                $this->bookCategories->deleteByBookId($book->id, $categories);
                $this->bookCategories->createByBookId($book->id, $categories);
            }

            return $book;
        } catch (Exception $exception) {
            $this->logError(LOG_UPDATE_BOOK, 'Update book error', [
                'message' => $exception->getMessage(),
                'data' => $data
            ]);
            // rethrow the exception
            throw $exception;
        }
    }

    /**
     * Check if a given value is a valid date string.
     *
     * @param mixed $date The value to check.
     * @return bool True if valid date, false otherwise.
     */
    private function isValidDate(mixed $date): bool
    {
        if (!is_string($date) && !is_numeric($date)) {
            return false;
        }

        try {
            Carbon::parse($date);
            return true;
        } catch (\Exception) {
            return false;
        }
    }
}
