<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\Interfaces\BookCategoriesRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\StreamInterface;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class BookService extends BaseService
{
    public function __construct(
        protected BookRepositoryInterface $repository,
        protected BookCategoriesRepositoryInterface $bookCategories,
        protected UploadFileService $uploadFileService
    ) {}

    /**
     * Get all books with pagination and filters
     *
     * @param array $filters
     * @return LengthAwarePaginator|null
     * @throws Exception
     */
    public function getAllBook(array $filters = []): LengthAwarePaginator|null
    {
        try {
            return $this->repository->getAllBookQuery($filters);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_GET_ALL_BOOK, 'Service get all book error', [
                'message' => $exception->getMessage(),
                'filters' => $filters
            ]);

            throw new Exception('Error fetching books', $exception->getCode(), $exception);
        }
    }

    /**
     * Create a new book and upload the book image
     *
     * @param array $data
     * @param File|StreamInterface|UploadedFile|null $file
     * @return Book|null
     * @throws Exception
     */
    public function createNewBook(array $data, File|StreamInterface|UploadedFile|null $file = null): Book|null
    {
        try {
            $book = null;

            DB::transaction(function () use ($data, $file, &$book) {
                //upload image
                $this->uploadImageBook($file, $data);

                //create book
                $book = $this->repository->createBook($data);
            });

            return $book;
        } catch (Exception $exception) {
            $this->logError(LOG_CREATE_BOOK, 'Service create book error', [
                'message' => $exception->getMessage(),
                'data' => $data
            ]);

            throw new Exception('Error creating book', $exception->getCode(), $exception);
        }
    }

    /**
     * Get book detail by id
     *
     * @param int|string $id
     * @return Book|null
     * @throws Exception
     */
    public function getBookDetail(int|string $id): Book|null
    {
        try {
            // Find book by id
            $book = $this->repository->findById($id);

            if ($book instanceof Book) {
                $book->categoryIds = $book->categories->pluck('id')->toArray();
            }

            return $book;
        } catch (Exception $exception) {
            $this->logError(LOG_GET_BOOK_DETAIL, 'Service get book detail error', [
                'message' => $exception->getMessage(),
                'book_id' => $id
            ]);

            // Set the code to the exception code or 500 if not set
            $code = $exception->getCode() ?: 500;

            // If the exception is a ModelNotFoundException, set the code to 404
            if ($exception instanceof ModelNotFoundException) throw $exception;

            throw new Exception('Error fetching book detail',  $code, $exception);
        }
    }

    /**
     * update book by id and upload the book image
     *
     * @param int $id
     * @param array $data
     * @param File|StreamInterface|UploadedFile|null $file
     * @return Book|null
     * @throws Exception
     */
    public function updateBook(int $id, array $data,File|StreamInterface|UploadedFile|null $file = null): Book|null
    {
        $book = null;

        try {
            DB::transaction(function () use ($id, $data, $file, &$book) {
                //upload image
                $this->uploadImageBook($file, $data);

                $book = $this->repository->updateBookById($id, $data);
            });
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_UPDATE_BOOK, 'Service update book error', [
                'message' => $exception->getMessage(),
                'data' => $data,
                'book_id' => $id
            ]);

            // If the exception is a ModelNotFoundException, set the code to 404
            if ($exception instanceof ModelNotFoundException) throw $exception;

            throw new Exception('Error updating book', $exception->getCode(), $exception);
        }

        return $book;
    }

    /**
     * Delete book by id
     *
     * @param int $id
     * @return bool|null
     * @throws ModelNotFoundException|Exception
     */
    public function deleteBook(int $id): bool|null
    {
        $isDelete = null;

        try {
            DB::transaction(function () use ($id, &$isDelete) {
                $book = $this->repository->findById($id);

                if ($book instanceof Book) {
                    $isDelete = $book->delete();
                }
            });

            return $isDelete;
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_DELETE_BOOK, 'Service delete book error', [
                'message' => $exception->getMessage(),
                'book_id' => $id
            ]);

            // If the exception is a ModelNotFoundException, set the code to 404
            if ($exception instanceof ModelNotFoundException) throw $exception;

            throw new Exception('Error deleting book', $exception->getCode(), $exception);
        }
    }

    /**
     * Upload book image and set the image path in data array
     *
     * @param string|File|StreamInterface|UploadedFile|null $file
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function uploadImageBook(File|StreamInterface|string|UploadedFile|null $file, array &$data): void
    {
        if ($file != null) {
            $randomName = uniqid() . '.' . $file->getClientOriginalExtension();
            $pathImage = config('filesystems.paths.book.image') . '/' . $randomName;
            $data["image"] = $pathImage;

            $this->uploadFileService->uploadFile($pathImage, $file);
        }
    }
}
