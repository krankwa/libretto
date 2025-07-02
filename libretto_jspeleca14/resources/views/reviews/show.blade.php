@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Review Details</h1>
    <p><strong>Book:</strong> {{ $review->book->title }}</p>
    <p><strong>Content:</strong> {{ $review->content }}</p>
    <p><strong>Rating:</strong> {{ $review->rating }}</p>
    <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Reviews</a>
</div>
@endsection