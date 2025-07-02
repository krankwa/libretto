@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Genre Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Name: {{ $genre->name }}</h5>
            <p class="card-text">ID: {{ $genre->id }}</p>
            <h5>Books:</h5>
            <ul>
                @foreach ($genre->books as $book)
                <li>{{ $book->title }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <a href="{{ route('genres.index') }}" class="btn btn-primary mt-3">Back to Genres</a>
</div>
@endsection