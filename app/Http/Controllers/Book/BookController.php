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
                'q',
                'published_at',
                'categories',
            ]);
            // get data
            $data = $this->service->getAllBook($filters);
            $categories = $this->categoryRepository->all();

            return view('pages.book.index', ['data' => $data, 'categories' => $categories, 'filters' => $filters]);
        } catch (Exception $e) {
            Log::error('Controller book index error', [
                'message' => $e->getMessage(),
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
     * Create new book
     *
     * @return View
     */
    public function create(): View
    {
        try {
            // get all categories
            $categories = $this->categoryRepository->all();
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
     * @param BookRequest $request
     * @return RedirectResponse
     */
    public function store(BookRequest $request): RedirectResponse
    {
        try {
            // get data
            $data = $request->only([
                'name',
                'author',
                'published_at',
                'categories',
                'description'
            ]);

            $file = $request->file('image');

            $isCreatedBook = $this->service->createNewBook($data, $file);

            if ($isCreatedBook) {
                return redirect()
                    ->route('books.index')
                    ->with('success', 'Create Book success');
            } else {
                return redirect()
                    ->back()
                    ->with('error', "Create Book Error!, Please create again");
            }
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_CREATE_BOOK, 'Error creating book', [
                'message' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return redirect()
                ->back()
                ->with('success', "Create Book Error!, Please create again");
        }
    }

    /**
     * Show book detail
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        try {
            // get book detail
            $book = $this->service->getBookDetail($id);
            $categories = $this->categoryRepository->all();

            return view('pages.book.edit', ['book' => $book, 'categories' => $categories]);
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_GET_BOOK_DETAIL, 'Error fetching book detail', [
                'message' => $e->getMessage(),
                'book_id' => $id,
            ]);
            // If the book is not found, return a 404 error
            if ($e instanceof ModelNotFoundException) throw $e;

            return view('pages.book.edit', ['book' => null, 'categories' => []])
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
            // get data
            $data = $request->only([
                'name',
                'author',
                'published_at',
                'categories',
                'description'
            ]);
            $file = $request->file('image');

            // update book
            $isUpdatedBook = $this->service->updateBook($id, $data, $file);
            if ($isUpdatedBook) {
                return redirect()
                    ->route('books.show', ['book' => $id])
                    ->with('success', 'Cập nhật sách thành công');
            } else {
                return redirect()
                    ->back()
                    ->with('error', "Cập nhật sách thất bại! Vui lòng thử lại sau");
            }
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_UPDATE_BOOK, 'Error updating book', [
                'message' => $e->getMessage(),
                'book_id' => $id,
                'data' => $request->all(),
            ]);
            // If the book is not found, return a 404 error
            if ($e instanceof ModelNotFoundException) throw $e;
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

            $isDeleted = $this->service->deleteBook($id);

            if ($isDeleted) {
                return redirect()
                    ->back()
                    ->with('success', 'Xía sách thành công');
            } else {
                $errorMessage = $isDeleted === null ? 'Không tìm thấy sách' . $id : 'Xoá sách thất bại! vui lòng thử lại sau';
                return redirect()
                    ->back()
                    ->with('error', $errorMessage);
            }
        } catch (Exception $e) {
            // Log the error message
            $this->logError(LOG_DELETE_BOOK, 'Error deleting book', [
                'message' => $e->getMessage(),
                'book_id' => $id,
            ]);
            // If the book is not found, return a 404 error
            if ($e instanceof ModelNotFoundException) throw $e;
            return redirect()
                ->back()
                ->with('error', 'Hệ thống đang lỗi, Xoá sách thất bại! vui lòng thử lại sau');
        }
    }
}
