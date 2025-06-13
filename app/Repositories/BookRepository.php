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

class BookRepository extends BaseRepository implements BookRepositoryInterface
{

    public function __construct(
        protected BookCategoriesRepositoryInterface $bookCategories
    ) {
        $this->model = app(Book::class);
    }

    /**
     * create new book query
     * @param array $data
     * @return Model|string|Exception
     * @throws Exception
     */
    public function createBook(array $data): Model|string|Exception
    {
        try {
            // create book
            $book = $this->create($data);
            //create book categories
            if ($book instanceof Book) {
                $categories = @$data["categories"] ?? [];
                $this->bookCategories->createBookCategoriesByBookId($book->id, $categories);
            }
            return $book;
        } catch (Exception $exception) {
            // log error message
            $this->logError(LOG_CREATE_BOOK, 'Create book error', [
                'message' => $exception->getMessage(),
                'data' => $data
            ]);

            throw $exception;
        }
    }

    /**
     * get all books
     * @param mixed $status
     * @param mixed $filters
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function getAllBookQuery(array $filters = [], $status = BookStatusEnum::ACTIVE->value): LengthAwarePaginator
    {
        try {
            return $this->model
                ->with(['categories'])
                ->when(isset($filters['categories']), function ($query) use ($filters) {
                    return $query->whereHas('categories', function ($query) use ($filters) {
                        return $query->whereIn('categories.id', $filters['categories']);
                    });
                })
                ->when(isset($filters['q']), function ($query) use ($filters) {
                    return $query->search($filters['q']);
                })
                ->when(isset($filters['published_at']), function ($query) use ($filters) {
                    return $query->WhereDate('published_at', Carbon::parse($filters['published_at']));
                })
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
     * Summary of updateBookById
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
                $categories = @$data["categories"] ?? [];
                // delete old categories and create new categories
                $this->bookCategories->deleteBookCategoriesByBookId($book->id, $categories);
                $this->bookCategories->createBookCategoriesByBookId($book->id, $categories);
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
}
