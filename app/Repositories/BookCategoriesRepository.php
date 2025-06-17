<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BookCategoriesRepositoryInterface;
use App\Models\BookCategory;
use Exception;

class BookCategoriesRepository extends BaseRepository implements BookCategoriesRepositoryInterface
{
    public function __construct()
    {
        $this->model = app(BookCategory::class);
    }

    /**
     * Delete book categories by book ID
     *
     * @param int $bookId
     * @param array $categoryIds
     * @return bool
     * @throws Exception
     */
    public function deleteByBookId(int $bookId, array $categoryIds = []): bool
    {
        try {
            // If categoryIds is empty, do not delete anything to avoid accidental mass deletion
            if (empty($categoryIds)) {
                return false;
            }

            return $this->model
                ->query()
                ->whereNotIn("category_id", $categoryIds)
                ->where('book_id', $bookId)
                ->delete();
        } catch (Exception $exception) {
            // Log error message
            $this->logError(LOG_DELETE_BOOK_CATEGORIES, 'Delete book categories error', [
                'message' => $exception->getMessage(),
                'book_id' => $bookId,
                'category_ids' => $categoryIds
            ]);

            // Rethrow the exception for further handling
            throw $exception;
        }
    }

    /**
     * Create or update book categories by book ID.
     *
     * @param int $bookId
     * @param array $categoryIds
     * @return void
     * @throws Exception
     */
    public function createByBookId(int $bookId, array $categoryIds = []): void
    {
        try {
            // Check if there are no categories to create
            collect($categoryIds)->chunk(100)->each(function ($categories) use ($bookId) {
                foreach ($categories as $categoryId) {
                    $this->model->updateOrCreate([
                        'book_id' => $bookId,
                        'category_id' => $categoryId
                    ], [
                        'book_id' => $bookId,
                        'category_id' => $categoryId
                    ]);
                }
            });
        } catch (Exception $exception) {
            // Log error message
            $this->logError(LOG_CREATE_BOOK_CATEGORIES, 'Create book categories error', [
                'message' => $exception->getMessage(),
                'book_id' => $bookId,
                'category_ids' => $categoryIds
            ]);

            // Rethrow the exception for further handling
            throw $exception;
        }
    }
}
