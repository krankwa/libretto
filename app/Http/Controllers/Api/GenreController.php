<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $genres = Genre::with('books')->get();
            return response()->json([
                'success' => true,
                'data' => $genres
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching genres',
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
                'name' => 'required|string|max:255',
            ]);

            $genre = Genre::create($request->only(['name']));

            return response()->json([
                'success' => true,
                'message' => 'Genre created successfully',
                'data' => $genre
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating genre',
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
            $genre = Genre::with('books')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $genre
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Genre not found',
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
                'name' => 'required|string|max:255',
            ]);

            $genre = Genre::findOrFail($id);
            $genre->update($request->only(['name']));

            return response()->json([
                'success' => true,
                'message' => 'Genre updated successfully',
                'data' => $genre
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating genre',
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
            $genre = Genre::findOrFail($id);
            $genre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Genre deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting genre',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
