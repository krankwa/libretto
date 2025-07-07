<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reviews = Review::with('book')->get();
            return response()->json([
                'success' => true,
                'data' => $reviews
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching reviews',
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
                'book_id' => 'required|exists:books,id',
                'content' => 'required|string|min:10|max:1000',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            $review = Review::create($request->only(['book_id', 'content', 'rating']));
            $review->load('book');

            return response()->json([
                'success' => true,
                'message' => 'Review created successfully',
                'data' => $review
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating review',
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
            $review = Review::with('book')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $review
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found',
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
                'book_id' => 'required|exists:books,id',
                'content' => 'required|string|min:10|max:1000',
                'rating' => 'required|integer|min:1|max:5',
            ]);

            $review = Review::findOrFail($id);
            $review->update($request->only(['book_id', 'content', 'rating']));
            $review->load('book');

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating review',
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
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}