<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css') <!-- Ensure your CSS is loaded -->
     <link href="https://cdn.jsdelivr.net/npm/flowbite@3.0.0/dist/flowbite.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-50 h-screen shadow-lg">
            <div class="p-4">
                <h1 class="text-lg font-semibold text-gray-700">ShopClip</h1>
            </div>
            <nav class="mt-5">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reg') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg">Register</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow px-4 py-2 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-700">@yield('header', 'Dashboard')</h2>
                <button type="button" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg sm:hidden">
                    <span class="sr-only">Toggle sidebar</span>
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </header>

            <!-- Content -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

     <script src="https://cdn.jsdelivr.net/npm/flowbite@3.0.0/dist/flowbite.min.js"></script>
</body>
</html>
