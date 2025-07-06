@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reviews</h1>
    <a href="{{ route('reviews.create') }}" class="btn btn-primary mb-3">Add Review</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Book</th>
                <th>Content</th>
                <th>Rating</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
            <tr>
                <td>{{ $review->id }}</td>
                <td>{{ $review->book->title }}</td>
                <td>{{ $review->content }}</td>
                <td>{{ $review->rating }}</td>
                <td>
                    <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection