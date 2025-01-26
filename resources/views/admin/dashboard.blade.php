@extends('layouts.app2')

@section('title', 'Table Page')
@section('header', 'Dashboard')

@section('content')
<body class="bg-gray-100">

    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-center mb-8">Admin Dashboard</h1>

        <!-- Date Picker for Filtering Transactions -->
        <div class="mb-6">
            <form action="{{ route('admin.dashboard') }}" method="GET">
                <label for="date" class="block text-sm font-medium text-gray-700">Select Date</label>
                <div class="flex items-center gap-2">
                    <input
                        type="date"
                        name="date"
                        id="date"
                        value="{{ $currentDate }}"
                        class="mt-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <button type="submit" class="mt-1 bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                        <i class="fa fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Display Users and Their Transactions -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Transactions ({{ $currentDate }})</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($users as $user)
                        <tr class="text-center border-b">
                            <td class="px-4 py-2">{{ $user->id }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->role }}</td>
                            <td class="px-4 py-2">
                                @if ($user->transactions->isEmpty())
                                    <span class="text-sm text-gray-600">No transactions found.</span>
                                @else
                                    <div class="space-y-2">
                                        @foreach ($user->transactions as $transaction)
                                            <div class="text-left bg-gray-50 p-2 rounded-md">
                                                <p class="text-sm"><strong>Transaction ID:</strong> {{ $transaction->id }}</p>
                                                <p class="text-sm"><strong>Total Amount:</strong> K{{ number_format($transaction->total_amount, 2) }}</p>
                                                <p class="text-sm"><strong>Created At:</strong> {{ $transaction->created_at->format('Y-m-d H:i:s') }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.0.0/dist/flowbite.min.js"></script>
</body>
</html>
@endsection