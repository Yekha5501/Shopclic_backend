<!-- resources/views/products/import.blade.php -->
<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Products from Excel') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4"> 

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md flex items-center">
                    <i class="fa fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Guiding Text Card -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center mb-4">
                    <i class="fa fa-info-circle text-blue-500 mr-2"></i> Guidelines for Importing Products
                </h3>
                <p class="text-gray-700 mb-2">Please ensure your Excel file meets the following format:</p>
                <ul class="list-disc ml-6 text-gray-700 space-y-2">
                    <li>
                        <strong><i class="fa fa-columns text-green-500 mr-2"></i>A1:</strong> <span>name</span>
                    </li>
                    <li>
                        <strong><i class="fa fa-columns text-green-500 mr-2"></i>B1:</strong> <span>price</span>
                    </li>
                    <li>
                        <strong><i class="fa fa-columns text-green-500 mr-2"></i>C1:</strong> <span>stock</span>
                    </li>
                </ul>
                <p class="text-gray-700 mt-4">
                    All rows after the headers should contain the product details, such as the product name, price, and stock quantity.
                </p>
            </div>

            <!-- Import Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form action="{{ route('import.excel.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-4 max-w-2xl mx-auto">

                            <!-- File Input -->
                            <div class="flex flex-col">
                                <label for="file" class="text-gray-700 font-semibold mb-2">Select Excel File</label>
                                <input 
                                    type="file" 
                                    id="file" 
                                    name="file" 
                                    class="border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3" 
                                    required>

                                @error('file')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                    class="w-full bg-blue-500 text-white py-3 px-6 rounded-lg shadow hover:bg-blue-600 focus:ring focus:ring-blue-300">
                                    <i class="fa fa-upload mr-2"></i> Upload File
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
