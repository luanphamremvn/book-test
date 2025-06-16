<?php

namespace App\Providers;

use App\Repositories\BookCategoriesRepository;
use App\Repositories\BookRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\Interfaces\BookCategoriesRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookCategoriesRepositoryInterface::class, BookCategoriesRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoriesRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
