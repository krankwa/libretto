<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('welcome');
});

// Author Routes
Route::resource('authors', AuthorController::class);

// Book Routes
Route::resource('books', BookController::class);

// Genre Routes
Route::resource('genres', GenreController::class);

// Review Routes
Route::resource('reviews', ReviewController::class);