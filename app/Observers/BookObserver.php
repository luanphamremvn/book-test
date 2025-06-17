<?php

namespace App\Observers;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Services\UploadFileService;
use App\Traits\LogTrait;
use Exception;
use Illuminate\Support\Str;

class BookObserver
{
    use LogTrait;

    /**
     * The log prefix for the observer.
     *
     * @var string
     */
    protected string $logPrefix = 'BookObserver';

    /**
     * Create a new observer instance.
     *
     * @param UploadFileService $uploadFileService
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(
        protected UploadFileService $uploadFileService,
        protected BookRepositoryInterface $bookRepository
    ) {}

    /**
     * Handle the Book "creating" event.
     *
     * This method is triggered before a Book model is created in the database.
     * It generates a unique slug for the book based on its name and the current count of books with the same name.
     * If an error occurs during slug generation, it logs the error and rethrows the exception.
     *
     * @param Book $book
     * @throws Exception
     */
    public function creating(Book $book): void
    {
        try {
            $countBook = $this->bookRepository->count(['name' => $book->name]);
            $book->slug = Str::slug($book->name) . "-" .  $countBook + 1;
        } catch (Exception $exception) {
            // Log the error message
            $this->logError($this->logPrefix, 'Error creating book slug', [
                'message' => $exception->getMessage(),
                'book_name' => $book->name,
            ]);

            // Rethrow the exception to ensure it is handled by the application
            throw $exception;
        }
    }

    /**
     * Handle the Book "updated" event.
     *
     * This method is triggered after a Book model is updated in the database.
     * If the image attribute has changed and the original image exists, it deletes the old image file.
     * If the name attribute has changed, it regenerates the slug for the book.
     * Any exceptions are logged and rethrown for further handling.
     *
     * @param Book $book
     * @throws Exception
     */
    public function updated(Book $book): void
    {
        try {
            // If the image is dirty and the original image is not null, delete the old image
            if ($book->isDirty("image") && $book->getOriginal('image') != null) {
                $this->uploadFileService->deleteFile($book->getOriginal('image'));
            }

            if ($book->isDirty("name")) {
                $countBook = $this->bookRepository->count(['name' => $book->name]);
                $book->slug = Str::slug($book->name) . "-" .  $countBook + 1;
            }
        } catch (Exception $e) {
            // Log the error message
            $this->logError($this->logPrefix, 'Error updating book image', [
                'message' => $e->getMessage(),
                'book_id' => $book->id,
            ]);

            // Rethrow the exception to ensure it is handled by the application
            throw $e;
        }
    }

    /**
     * Handle the Book "deleted" event.
     *
     * This method is triggered after a Book model is deleted from the database.
     * It deletes the associated image file from storage if it exists.
     * Any exceptions are logged and rethrown for further handling.
     *
     * @param Book $book The Book model instance being deleted.
     * @throws Exception If file deletion fails.
     */
    public function deleted(Book $book): void
    {
        try {
            // Delete the old image if it exists
            if ($book->image != null) {
                $this->uploadFileService->deleteFile($book->image);
            }
        } catch (Exception $e) {
            // Log the error message
            $this->logError($this->logPrefix, 'Error deleting book categories', [
                'message' => $e->getMessage(),
                'book_id' => $book->id,
            ]);

            throw $e;
        }
    }
}
