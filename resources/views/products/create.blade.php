<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Product') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4"> 

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <!-- Bulk Import Note -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-blue-700 flex items-center mb-4">
                    <i class="fa fa-upload text-blue-500 mr-2"></i> Bulk Import Available
                </h3>
                <p class="text-gray-700">
                    Save time by importing products in bulk using our Excel import feature. 
                    Visit the <a href="{{ route('import.excel.form') }}" class="text-blue-600 font-semibold underline">
                    Import Products</a> page to upload your file.
                </p>
                <p class="text-gray-700 mt-2">
                    Ensure your file matches the required format with columns for <strong>name</strong>, 
                    <strong>price</strong>, and <strong>stock</strong>. This feature is ideal for managing large product inventories.
                </p>
            </div>

            <!-- Create Product Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Form Container with limited width -->
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                        <div class="space-y-4 max-w-2xl mx-auto">

                            <!-- Product Name -->
                            <div class="flex flex-col">
                                <label for="name" class="text-gray-700 font-semibold mb-2">Product Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                    class="border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"
                                    placeholder="Enter product name" required>

                                @error('name')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Price -->
                            <div class="flex flex-col">
                                <label for="price" class="text-gray-700 font-semibold mb-2">Price</label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" 
                                    class="border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"
                                    placeholder="Enter price" required>

                                @error('price')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Product Stock -->
                            <div class="flex flex-col">
                                <label for="stock" class="text-gray-700 font-semibold mb-2">Stock</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}" 
                                    class="border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3"
                                    placeholder="Enter stock quantity" required>

                                @error('stock')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                    class="w-full bg-green-500 text-white py-3 px-6 rounded-lg shadow hover:bg-blue-600 focus:ring focus:ring-blue-300">
                                    <i class="fa fa-save mr-2"></i> Save Product
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
