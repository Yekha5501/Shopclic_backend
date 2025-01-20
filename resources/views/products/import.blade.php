<!-- resources/views/products/import.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Import Products from Excel</h1>

    <form action="{{ route('products.import.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Select Excel File</label>
            <input type="file" id="file" name="file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
@endsection
