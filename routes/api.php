<?php

use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\SimpleAuthController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;

// Test routes
Route::get('test', [TestController::class, 'test']);

// Public authentication routes
Route::post('register', [SimpleAuthController::class, 'register']);
Route::post('login', [SimpleAuthController::class, 'login']);

// Protected routes requiring authentication
Route::middleware(['auth:sanctum', 'token.expiry'])->group(function () {
    // Test auth route
    Route::get('test-auth', [TestController::class, 'testAuth']);
    
    // Authentication management routes
    Route::post('logout', [SimpleAuthController::class, 'logout']);
    Route::get('user', [SimpleAuthController::class, 'user']);
    Route::post('refresh', [SimpleAuthController::class, 'refresh']);
    
    // Resource routes
    Route::apiResource('reviews', ReviewController::class);
    Route::apiResource('books', BookController::class);
    Route::apiResource('authors', AuthorController::class);
    Route::apiResource('genres', GenreController::class);
});