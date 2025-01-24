<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <!-- Row with Top Selling Products Card and Transaction Dates -->
            <div class="grid grid-cols-12 gap-6">

                <!-- Top Selling Products Card (4/12) -->
                <div class="col-span-12 lg:col-span-4 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-chart-line text-blue-500 mr-2"></i> Top Selling Products
                    </h3>
                    <ul class="space-y-4">
                        @foreach ($topProducts as $product)
                        <li class="flex items-center justify-between bg-gray-100 p-3 rounded-md">
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    <i class="fa fa-box text-gray-600 mr-2"></i>
                                    {{ $product->product->name }}
                                </h4>
                                <p class="text-sm text-gray-600">
                                    <i class="fa fa-shopping-cart text-gray-400 mr-1"></i>
                                    Quantity: {{ $product->total_quantity }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fa fa-dollar-sign text-gray-400 mr-1"></i>
                                    Revenue: ${{ number_format($product->total_revenue, 2) }}
                                </p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Transaction Dates (8/12) -->
                <div class="col-span-12 lg:col-span-8">
                    <div class="overflow-x-auto bg-white shadow-md rounded-md p-4">
                        <h3 class="font-semibold text-lg text-gray-700 mb-4">
                            <i class="fa fa-calendar text-blue-500 mr-2"></i> Transaction Dates
                        </h3>
                        <table class="table-auto w-full text-left border-collapse border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200">Date</th>
                                    <th class="px-4 py-2 border border-gray-200">Revenue ($)</th>
                                    <th class="px-4 py-2 border border-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactionsByDateFormatted as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border border-gray-200">{{ $data->date }}</td>
                                    <td class="px-4 py-2 border border-gray-200">{{ number_format($data->revenue, 2) }}</td>
                                    <td class="px-4 py-2 border border-gray-200">
                                        <a href="{{ route('reports.transactions-by-date', $data->date) }}" class="text-blue-500 hover:text-blue-700">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Reverted Transactions Table -->
            <div class="mt-8">
                <div class="overflow-x-auto bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-undo text-red-500 mr-2"></i> Reverted Transactions
                    </h3>
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
