<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4"> 
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                            <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end gap-4">
                        <!-- Add New Product Button -->
                        <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 focus:ring focus:ring-blue-300">
                            <i class="fa fa-plus mr-2"></i> Add Product
                        </a>

                        <!-- Import Products Button -->
                        <a href="{{ route('import.excel.form') }}" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 focus:ring focus:ring-green-300">
                            <i class="fa fa-upload mr-2"></i> Import Products in Excel
                        </a>
                    </div>

                    <!-- Search Input -->
                    <div class="mb-4">
                        <form action="{{ route('products.index') }}" method="GET">
                            <input
                                type="text"
                                name="search"
                                id="searchProducts"
                                placeholder="Search products..."
                                value="{{ request('search') }}"
                                class="w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                                <i class="fa fa-search"></i>
                            </button>
                            <!-- Clear Search Button -->
                            @if (request('search'))
                                <a href="{{ route('products.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-800">
                                    <i class="fa fa-times"></i> Clear Search
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-left border-collapse border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200">ID</th>
                                    <th class="px-4 py-2 border border-gray-200">Name</th>
                                    <th class="px-4 py-2 border border-gray-200">Price (K)</th>
                                    <th class="px-4 py-2 border border-gray-200">Stock</th>
                                    <th class="px-4 py-2 border border-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border border-gray-200">{{ $product->id }}</td>
                                        <td class="px-4 py-2 border border-gray-200">{{ $product->name }}</td>
                                        <td class="px-4 py-2 border border-gray-200">{{ number_format($product->price, 2) }}</td>
                                        <td class="px-4 py-2 border border-gray-200">
                                            {{ $product->stock }}
                                            @if ($product->stock == 0)
                                                <span class="text-red-500 text-sm">(Out of Stock)</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border border-gray-200 flex gap-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                            <!-- View Button -->
                                            <a href="{{ route('products.show', $product->id) }}" class="text-green-500 hover:text-green-700">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links with Search Parameter -->
                        <div class="mt-4">
                            {{ $products->appends(['search' => request('search')])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>