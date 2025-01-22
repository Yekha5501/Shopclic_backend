<x-app-layout>

    <style>
       
    </style>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- First Row: Three Cards -->
            <div class="grid grid-cols-12 gap-6 mb-6">

                <!-- Card 1: Total Stock Worth -->
                <div class="col-span-12 sm:col-span-4 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-cogs text-blue-500 mr-2"></i> Total Stock Worth
                    </h3>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($totalStockWorth, 2) }}</p>
                </div>

                <!-- Card 2: Revenue of the Day -->
                <div class="col-span-12 sm:col-span-4 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-dollar-sign text-green-500 mr-2"></i> Revenue Today
                    </h3>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($revenueToday, 2) }}</p>
                </div>

                <!-- Card 3: Total Transactions for Today -->
                <div class="col-span-12 sm:col-span-4 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-shopping-cart text-yellow-500 mr-2"></i> Total Transactions Today
                    </h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTransactionsToday }}</p>
                </div>

            </div>

            <!-- Second Row: Chart and List -->
            <div class="grid grid-cols-12 gap-6 mb-6">

                <!-- Chart Card (8/12) -->
                <div class="col-span-12 sm:col-span-8 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-chart-line text-blue-500 mr-2"></i> Sales Overview (7 Days)
                    </h3>
                    <!-- Chart Here (You can use a chart library like ApexCharts or Chart.js) -->
                    <!-- Placeholder for Chart -->
                    <div id="chart"></div>
                </div>

                <!-- List Card (4/12) -->
                <div class="col-span-12 sm:col-span-4 bg-white shadow-md rounded-md p-4">
    <h3 class="font-semibold text-lg text-gray-700 mb-4">
        <i class="fa fa-boxes text-gray-600 mr-2"></i> Top-Selling Products
    </h3>
    <ul class="space-y-4">
        @foreach ($topProducts as $product)
            <li class="flex items-center bg-gray-100 p-3 rounded-md">
                <!-- Product Name -->
                <h4 class="font-semibold text-gray-800 flex-shrink-0 w-1/3">
                    {{ $product->product->name }}
                </h4>
                <!-- Product Details -->
                <div class="ml-4 flex-1 text-sm text-gray-600">
                    <p>Quantity Sold: {{ $product->total_quantity }}</p>
                    <p>Revenue: ${{ number_format($product->total_revenue, 2) }}</p>
                </div>
            </li>
        @endforeach
    </ul>
</div>


            </div>

            <!-- Third Row: Product Stock Table -->
            <div class="col-span-12 bg-white shadow-md rounded-md p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg text-gray-700">
                        <i class="fa fa-warehouse text-gray-600 mr-2"></i> Product Stock Levels
                    </h3>
                    <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">View All</a>
                </div>
                <table class="table-auto w-full text-left border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border border-gray-200">Product</th>
                            <th class="px-4 py-2 border border-gray-200">Stock Level</th>
                            <th class="px-4 py-2 border border-gray-200">Price</th>
                            <th class="px-4 py-2 border border-gray-200">Stock Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border border-gray-200">{{ $product->name }}</td>
                            <td class="px-4 py-2 border border-gray-200">{{ $product->stock }}</td>
                            <td class="px-4 py-2 border border-gray-200">${{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-2 border border-gray-200">
                                @if($product->stock > 10)
                                <span class="text-green-500">In Stock</span>
                                @elseif($product->stock > 0)
                                <span class="text-yellow-500">Low Stock</span>
                                @else
                                <span class="text-red-500">Out of Stock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>



   <script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartOptions = {
            series: [{
                name: "Sales",
                data: @json($chartData['sales'])
            }, {
                name: "Stock Worth After Sales",
                data: @json($chartData['stockWorth'])
            }],
            chart: {
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.5
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#77B6EA', '#FF6347'], // Blue for Sales, Red for Stock Worth
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Sales and Stock Worth Trend (Last 7 Days)',
                align: 'left'
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                }
            },
            markers: {
                size: 1
            },
            xaxis: {
                categories: @json($chartData['dates']),
                title: {
                    text: 'Date'
                }
            },
            yaxis: [{
                title: {
                    text: 'Total Sales'
                },
                min: 0
            }, {
                opposite: true,
                title: {
                    text: 'Stock Worth After Sales'
                },
                min: 0
            }],
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart"), chartOptions);
        chart.render();
    });
</script>


</x-app-layout>