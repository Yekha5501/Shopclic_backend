<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transactions on ') }} ( {{ $formattedDate }})
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Display transactions for the selected date -->
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-left border-collapse border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200">Transaction ID</th>
                                    <th class="px-4 py-2 border border-gray-200">Product</th>
                                    <th class="px-4 py-2 border border-gray-200">Quantity</th>
                                    <th class="px-4 py-2 border border-gray-200">Subtotal ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    @foreach ($transaction->transactionItems as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border border-gray-200">{{ $transaction->id }}</td>
                                            <td class="px-4 py-2 border border-gray-200">{{ $item->product->name }}</td>
                                            <td class="px-4 py-2 border border-gray-200">{{ $item->quantity }}</td>
                                            <td class="px-4 py-2 border border-gray-200">{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <!-- Display total revenue for the day -->
                        <div class="bg-gray-100 p-4 rounded-md">
                            <h4 class="font-semibold text-lg text-gray-700">
                                Total Revenue: ${{ number_format($transactions->sum('total_amount'), 2) }}
                            </h4>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('reports.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                            <i class="fa fa-arrow-left mr-2"></i> Back to Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
