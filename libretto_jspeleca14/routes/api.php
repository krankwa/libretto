<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController; 
use App\Http\Controllers\GenreController;

Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('reviews', ReviewController::class);
    Route::resource('books', BookController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('genres', GenreController::class);
});