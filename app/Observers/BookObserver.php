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
    protected string $logPrefix = 'Observer';

    /**
     * Create a new observer instance.
     *
     * @param UploadFileService $uploadFileService
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(protected UploadFileService $uploadFileService, protected BookRepositoryInterface $bookRepository) {}

    /**
     * Handle the Book "creating" event.
     * @throws Exception
     */
    public function creating(Book $book): void
    {
        try {
            $countBook = $this->bookRepository->count(['name' => $book->name]);
            $book->slug = Str::slug($book->name) . "-" .  $countBook + 1;
        } catch (Exception $e) {
            // Log the error message
            $this->logError($this->logPrefix, 'Error creating book slug', [
                'message' => $e->getMessage(),
                'book_name' => $book->name,
            ]);
            throw $e;
        }
    }

    /**
     * Handle the Book "updated" event.
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
            throw $e;
        }
    }

    /**
     * Handle the Book "deleted" event.
     * @throws Exception
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
