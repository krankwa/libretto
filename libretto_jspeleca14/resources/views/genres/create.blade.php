@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Genre</h1>
    <form action="{{ route('genres.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Genre Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('genres.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection