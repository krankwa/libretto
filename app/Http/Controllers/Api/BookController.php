<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $books = Book::with(['author', 'genres'])->get();
            return response()->json([
                'success' => true,
                'data' => $books
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching books',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'author_id' => 'required|exists:authors,id',
                'genre_ids' => 'array',
                'genre_ids.*' => 'exists:genres,id'
            ]);

            $book = Book::create($request->only(['title', 'author_id']));
            
            if ($request->has('genre_ids')) {
                $book->genres()->attach($request->genre_ids);
            }

            $book->load(['author', 'genres']);

            return response()->json([
                'success' => true,
                'message' => 'Book created successfully',
                'data' => $book
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $book = Book::with(['author', 'genres', 'reviews'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $book
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'author_id' => 'required|exists:authors,id',
                'genre_ids' => 'array',
                'genre_ids.*' => 'exists:genres,id'
            ]);

            $book = Book::findOrFail($id);
            $book->update($request->only(['title', 'author_id']));

            if ($request->has('genre_ids')) {
                $book->genres()->sync($request->genre_ids);
            }

            $book->load(['author', 'genres']);

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully',
                'data' => $book
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();

            return response()->json([
                'success' => true,
                'message' => 'Book deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting book',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
