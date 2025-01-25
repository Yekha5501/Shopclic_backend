<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <style>
        @media (max-width: 640px) {
            .login-card {
                width: 90%;
                padding: 2rem;
                margin: 0;
                border-radius: 0;
                min-height: 10vh;
                border-radius: 5px;
            }
        }
    </style>
</head>
<body class="bg-gray-200">
<div class="min-h-screen flex items-center justify-center border-4 border-green-500">
    <div class="login-card max-w-md w-full mx-auto p-8 bg-white rounded-lg shadow-lg">
        <!-- Logo Section -->
        <div class="text-2xl font-bold text-center">{{ __('ShopKlip') }}</div>

        <div class="flex justify-center mt-4 mb-4">
    <img src="{{ asset('images/icon.png') }}" alt="Logo" class="h-24 w-24">
</div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="mt-4">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="text-red-500 text-xs mt-1" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                <input id="password" type="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                <span class="text-red-500 text-xs mt-1" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="mb-4 flex items-center">
                <input class="mr-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="text-sm text-gray-600" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            <div class="mb-4">
                <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __('Login') }}
                </button>
            </div>
            @if (Route::has('password.request'))
            <div class="text-sm text-center">
                <a class="text-green-500 hover:text-green-700" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
            @endif
        </form>
    </div>
</div>
</body>
</html>
