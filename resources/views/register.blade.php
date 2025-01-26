@extends('layouts.app2')

@section('title', 'Register')
@section('header', 'Register')

@section('content')
    <!-- Display success or error message -->
    @if (session('success'))
        <div class="mb-4 text-green-500">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 text-red-500">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Create a New Account</h2>

        <form method="POST" action="{{ route('reg.store') }}">
            @csrf
            <!-- Name Field -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>

            <!-- Submit Button -->
            <div class="mb-4">
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">Register</button>
            </div>
        </form>
    </div>
@endsection
