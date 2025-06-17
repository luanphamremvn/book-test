<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use App\Http\Requests\Book\BookFilterRequest;
use App\Services\BookService;
use App\Services\CategoryService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;

class BookController extends Controller
{
    public function __construct(
        protected BookService $bookService,
        protected CategoryService $categoryService
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

            $books = $this->bookService->getAllBook($filters);
            $categories = $this->categoryService->all();

            return view(
                'pages.book.index',
                [
                    'books' => $books,
                    'categories' => $categories,
                    'filters' => $filters
                ]
            );
        } catch (Exception $exception) {
            Log::error('Controller book index error', [
                'message' => $exception->getMessage(),
                'request' => $request->all(),
            ]);

            abort(500, 'Lỗi hệ thống, vui lòng thử lại sau');
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
            $categories = $this->categoryService->all();

            // Return the view with categories
            return view('pages.book.create', ['categories' => $categories]);
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_CREATE_BOOK, 'Error displaying book creation page', [
                'message' => $e->getMessage(),
            ]);

            abort(500, 'Lỗi hệ thống, vui lòng thử lại sau');
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
            $createdBook = $this->bookService->createNewBook($bookData, $imageFile);

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

            abort(500, 'Lỗi hệ thống, vui lòng thử lại sau');
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
            $bookDetail = $this->bookService->getBookDetail($id);
            $categories = $this->categoryService->all();

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

            abort(500, 'Lỗi hệ thống, vui lòng thử lại sau');
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
            $updatedBook = $this->bookService->updateBook($id, $bookData, $imageFile);
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

            abort(500, 'Lỗi hệ thống, vui lòng thử lại sau');
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
            $deletedBookSuccess = $this->bookService->deleteBook($id);

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
            abort(500, 'Hệ thống đang lỗi, Xoá sách thất bại! vui lòng thử lại sau');
        }
    }
}
