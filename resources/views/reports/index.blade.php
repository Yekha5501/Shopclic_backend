<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <!-- Row with Out of Stock and Low Stock Products Card and Transaction Dates -->
            <div class="grid grid-cols-12 gap-6">

                <!-- Out of Stock and Low Stock Products Card (4/12) -->
                <div class="col-span-12 lg:col-span-4 bg-white shadow-md rounded-md p-4">
                    <!-- Out of Stock Products -->
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-exclamation-triangle text-red-500 mr-2"></i> Out of Stock Products
                    </h3>
                    <ul class="space-y-4">
                        @forelse ($outOfStockProducts as $product)
                        <li class="flex items-center justify-between bg-gray-100 p-3 rounded-md">
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    <i class="fa fa-box text-gray-600 mr-2"></i>
                                    {{ $product->name }}
                                </h4>
                                <p class="text-sm text-gray-600">
                                    <i class="fa fa-cubes text-gray-400 mr-1"></i>
                                    Stock: {{ $product->stock }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fa fa-dollar-sign text-gray-400 mr-1"></i>
                                    Price: K{{ number_format($product->price, 2) }}
                                </p>
                            </div>
                        </li>
                        @empty
                        <li class="text-sm text-gray-600">No out-of-stock products found.</li>
                        @endforelse
                    </ul>

                    <!-- Low Stock Products -->
                    <h3 class="font-semibold text-lg text-gray-700 mt-8 mb-4">
                        <i class="fa fa-exclamation-circle text-yellow-500 mr-2"></i> Low Stock Products
                    </h3>
                    <ul class="space-y-4">
                        @forelse ($lowStockProducts as $product)
                        <li class="flex items-center justify-between bg-gray-100 p-3 rounded-md">
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    <i class="fa fa-box text-gray-600 mr-2"></i>
                                    {{ $product->name }}
                                </h4>
                                <p class="text-sm text-gray-600">
                                    <i class="fa fa-cubes text-gray-400 mr-1"></i>
                                    Stock: {{ $product->stock }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fa fa-dollar-sign text-gray-400 mr-1"></i>
                                    Price: K{{ number_format($product->price, 2) }}
                                </p>
                            </div>
                        </li>
                        @empty
                        <li class="text-sm text-gray-600">No low-stock products found.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Transaction Dates and Reverted Transactions (8/12) -->
                <div class="col-span-12 lg:col-span-8">
                    <!-- Transaction Dates Table -->
                    <div class="overflow-x-auto bg-white shadow-md rounded-md p-4 mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg text-gray-700">
                                <i class="fa fa-calendar text-green-500 mr-2"></i> Transaction Dates
                            </h3>
                            <!-- Date Search Input -->
                            <form action="{{ route('reports.index') }}" method="GET">
                                <input
                                    type="date"
                                    name="transaction_date"
                                    value="{{ $transactionDate }}"
                                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                />
                                <button type="submit" class="ml-2 bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">
                                    <i class="fa fa-search"></i>
                                </button>
                                @if ($transactionDate)
                                    <a href="{{ route('reports.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-800">
                                        <i class="fa fa-times"></i> Clear
                                    </a>
                                @endif
                            </form>
                        </div>
                        <table class="table-auto w-full text-left border-collapse border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200">Date</th>
                                    <th class="px-4 py-2 border border-gray-200">Revenue (K)</th>
                                    <th class="px-4 py-2 border border-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactionsByDateFormatted as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border border-gray-200">{{ $data->date }}</td>
                                    <td class="px-4 py-2 border border-gray-200">{{ number_format($data->revenue, 2) }}</td>
                                    <td class="px-4 py-2 border border-gray-200">
                                        <a href="{{ route('reports.transactions-by-date', $data->date) }}" class="text-green-500 hover:text-green-700">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $transactionsByDate->appends(['transaction_date' => $transactionDate])->links() }}
                        </div>
                    </div>

                    <!-- Reverted Transactions Table -->
                    <div class="overflow-x-auto bg-white shadow-md rounded-md p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold text-lg text-gray-700">
                                <i class="fa fa-undo text-yellow-500 mr-2"></i> Reverted Transactions
                            </h3>
                            <!-- Search Input -->
                            <form action="{{ route('reports.index') }}" method="GET">
                                <input
                                    type="text"
                                    name="reverted_transaction_search"
                                    placeholder="Search reverted transactions..."
                                    value="{{ $revertedTransactionSearch }}"
                                    class="w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                />
                                <button type="submit" class="ml-2 bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-600">
                                    <i class="fa fa-search"></i>
                                </button>
                                @if ($revertedTransactionSearch)
                                    <a href="{{ route('reports.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-800">
                                        <i class="fa fa-times"></i> Clear
                                    </a>
                                @endif
                            </form>
                        </div>
                        <table class="table-auto w-full text-left border-collapse border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200">ID</th>
                                    <th class="px-4 py-2 border border-gray-200">Transaction ID</th>
                                    <th class="px-4 py-2 border border-gray-200">Reverted By</th>
                                    <th class="px-4 py-2 border border-gray-200">Transaction Items</th>
                                    <th class="px-4 py-2 border border-gray-200">Reverted At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($revertedTransactions as $revertedTransaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border border-gray-200">{{ $revertedTransaction->id }}</td>
                                    <td class="px-4 py-2 border border-gray-200">{{ $revertedTransaction->transaction_id }}</td>
                                    <td class="px-4 py-2 border border-gray-200">{{ $revertedTransaction->user->name }}</td>
                                    <td class="px-4 py-2 border border-gray-200">
                                        <ul>
                                            @foreach ($revertedTransaction->transaction_items as $item)
                                            <li>{{ $item['product_name'] }} - Quantity: {{ $item['quantity'] }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-4 py-2 border border-gray-200">{{ $revertedTransaction->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 border border-gray-200 text-center">No reverted transactions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="mt-4">
                            {{ $revertedTransactions->appends(['reverted_transaction_search' => $revertedTransactionSearch])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>