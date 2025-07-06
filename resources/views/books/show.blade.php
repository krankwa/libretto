@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Book Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Title: {{ $book->title }}</h5>
            <p class="card-text">ID: {{ $book->id }}</p>
            <p class="card-text">Author: {{ $book->author->name }}</p>
            <h5>Genres:</h5>
            <ul>
                @foreach ($book->genres as $genre)
                <li>{{ $genre->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">Back to Books</a>
</div>
@endsection