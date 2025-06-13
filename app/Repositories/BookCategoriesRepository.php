<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BookCategoriesRepositoryInterface;
use App\Models\BookCategories;

class BookCategoriesRepository extends BaseRepository implements BookCategoriesRepositoryInterface
{

    public function __construct()
    {
        $this->model = app(BookCategories::class);
    }

    /**
     * Delete book categories by book ID
     * @param int $bookId
     * @param array $categoryIds
     * @return bool
     * @throws Exception
     */
    public function deleteBookCategoriesByBookId(int $bookId, array $categoryIds = []): bool
    {
        try {
            return $this->model->query()->whereNotIn("category_id", $categoryIds)
                ->where('book_id', $bookId)->delete();
        } catch (\Exception $e) {
            // Log error message
            $this->logError(LOG_DELETE_BOOK_CATEGORIES, 'Delete book categories error', [
                'message' => $e->getMessage(),
                'book_id' => $bookId,
                'category_ids' => $categoryIds
            ]);
            throw $e;
        }
    }

    /**
     * Create book categories by book ID
     * @param int $bookId
     * @param array $categoryIds
     * @return void
     * @throws Exception
     */
    public function createBookCategoriesByBookId(int $bookId, array $categoryIds = []): void
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
        } catch (\Exception $e) {
            // Log error message
            $this->logError(LOG_CREATE_BOOK_CATEGORIES, 'Create book categories error', [
                'message' => $e->getMessage(),
                'book_id' => $bookId,
                'category_ids' => $categoryIds
            ]);
        }
    }
}
