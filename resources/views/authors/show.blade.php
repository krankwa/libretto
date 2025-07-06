@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Author Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Name: {{ $author->name }}</h5>
            <p class="card-text">ID: {{ $author->id }}</p>
            <h5>Books:</h5>
            <ul>
                @foreach ($author->books as $book)
                <li>{{ $book->title }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <a href="{{ route('authors.index') }}" class="btn btn-primary mt-3">Back to Authors</a>
</div>
@endsection