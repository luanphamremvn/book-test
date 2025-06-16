<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use App\Http\Requests\Book\BookFilterRequest;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\BookService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;

class BookController extends Controller
{
    public function __construct(
        protected BookService $service,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * show all book
     *
     * @param BookFilterRequest $request
     * @return View
     */
    public function index(BookFilterRequest $request): View
    {
        try {
            //filters
            $filters = $request->only([
                'keyword',
                'published_at',
                'categories',
            ]);

            $books = $this->service->getAllBook($filters);
            $categories = $this->categoryRepository->all();

            return view(
                'pages.book.index',
                [
                    'data' => $books,
                    'categories' => $categories,
                    'filters' => $filters
                ]
            );
        } catch (Exception $exception) {
            Log::error('Controller book index error', [
                'message' => $exception->getMessage(),
                'request' => $request->all(),
            ]);

            // Return an empty view with an error message
            return view('pages.book.index', [
                'data' => [],
                'categories' => [],
                'filters' => [],
            ])->with(['errorMessage' => 'Lỗi hệ thống, vui lòng thử lại sau']);
        }
    }

    /**
     * Show create book form.
     *
     * @return View
     */
    public function create(): View
    {
        try {
            // get all categories
            $categories = $this->categoryRepository->all();

            // Return the view with categories
            return view('pages.book.create', ['categories' => $categories]);
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_CREATE_BOOK, 'Error displaying book creation page', [
                'message' => $e->getMessage(),
            ]);

            // Return an empty view with an error message
            return view('pages.book.create', ['categories' => []])
                ->with('errorMessage', 'Lỗi hệ thống, vui lòng thử lại sau');
        }
    }

    /**
     * Store new book.
     *
     * @param BookRequest $request
     * @return RedirectResponse
     */
    public function store(BookRequest $request): RedirectResponse
    {
        try {
            $bookData = $request->only([
                'name',
                'author',
                'published_at',
                'categories',
                'description'
            ]);

            $imageFile = $request->file('image');
            $createdBook = $this->service->createNewBook($bookData, $imageFile);

            if ($createdBook) {
                return redirect()
                    ->route('books.index')
                    ->with('success', 'Create Book success');
            }

            return redirect()
                ->back()
                ->with('error', "Create Book Error!, Please create again");
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_CREATE_BOOK, 'Error creating book', [
                'message' => $exception->getMessage(),
                'data' => $request->all(),
            ]);

            return redirect()
                ->back()
                ->with('success', "Create Book Error!, Please create again");
        }
    }

    /**
     * Show book detail.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        try {
            $bookDetail = $this->service->getBookDetail($id);
            $categories = $this->categoryRepository->all();

            return view('pages.book.edit', [
                'bookDetail' => $bookDetail,
                'categories' => $categories
            ]);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_GET_BOOK_DETAIL, 'Error fetching book detail', [
                'message' => $exception->getMessage(),
                'book_id' => $id,
            ]);

            // If the book is not found, return a 404 error
            if ($exception instanceof ModelNotFoundException) throw $exception;

            return view('pages.book.edit', ['bookDetail' => null, 'categories' => []])
                ->with('errorMessage', 'Lỗi hệ thống, vui lòng thử lại sau');
        }
    }


    /**
     * Update book by id
     *
     * @param BookRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(BookRequest $request, string $id): RedirectResponse
    {
        try {
            $bookData = $request->only([
                'name',
                'author',
                'published_at',
                'categories',
                'description'
            ]);
            $imageFile = $request->file('image');

            // update book
            $updatedBook = $this->service->updateBook($id, $bookData, $imageFile);
            if ($updatedBook) {
                return redirect()
                    ->route('books.show', ['book' => $id])
                    ->with('success', 'Cập nhật sách thành công');
            }

            return redirect()
                ->back()
                ->with('error', "Cập nhật sách thất bại! Vui lòng thử lại sau");
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_UPDATE_BOOK, 'Error updating book', [
                'message' => $exception->getMessage(),
                'book_id' => $id,
                'data' => $request->all(),
            ]);

            // If the book is not found, return a 404 error
            if ($exception instanceof ModelNotFoundException) throw $exception;

            // Return an error message
            return redirect()
                ->back()
                ->with('error', "Lỗi hệ thống, Cập nhật sách thất bại! Vui lòng thử lại sau");
        }
    }

    /**
     * Delete book by id
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $deletedBookSuccess = $this->service->deleteBook($id);

            if ($deletedBookSuccess) {
                return redirect()
                    ->back()
                    ->with('success', 'Xóa sách thành công');
            }

            $errorMessage = $deletedBookSuccess === null ? 'Không tìm thấy sách' . $id : 'Xóa sách thất bại! vui lòng thử lại sau';

            return redirect()
                ->back()
                ->with('error', $errorMessage);
        } catch (Exception $exception) {
            // Log the error message
            $this->logError(LOG_DELETE_BOOK, 'Error deleting book', [
                'message' => $exception->getMessage(),
                'book_id' => $id,
            ]);

            // If the book is not found, return a 404 error
            if ($exception instanceof ModelNotFoundException) throw $exception;

            // Return an error message
            return redirect()
                ->back()
                ->with('error', 'Hệ thống đang lỗi, Xoá sách thất bại! vui lòng thử lại sau');
        }
    }
}
