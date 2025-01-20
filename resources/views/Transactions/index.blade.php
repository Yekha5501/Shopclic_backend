<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                            <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end gap-4">
                        <!-- Add New Transaction Button -->
                        <a href="{{ route('transactions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 focus:ring focus:ring-blue-300">
                            <i class="fa fa-plus mr-2"></i> Add Transaction
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-left border-collapse border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border border-gray-200">Transaction ID</th>
                                    <th class="px-4 py-2 border border-gray-200">Total Amount ($)</th>
                                    <th class="px-4 py-2 border border-gray-200">Date</th>
                                    <th class="px-4 py-2 border border-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 border border-gray-200">{{ $transaction->id }}</td>
                                        <td class="px-4 py-2 border border-gray-200">{{ number_format($transaction->total_amount, 2) }}</td>
                                        <td class="px-4 py-2 border border-gray-200">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 border border-gray-200 flex gap-2">
                                            <!-- View Button -->
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="text-green-500 hover:text-green-700">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
