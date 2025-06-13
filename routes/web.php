<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Book\BookController;
use App\Http\Controllers\DashboardController;

Route::middleware(['web'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.authenticate');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');
    Route::resource('/books', BookController::class);
    Route::resource('/users', UserController::class);
});
