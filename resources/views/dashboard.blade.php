<x-app-layout>
    <x-slot name="header" class="bg-gray-100">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Dashboard Content -->
    <div class="min-h-screen bg-gray-100 p-6">
        <!-- Overview Section (First Row with Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <!-- Total Revenue Card -->
            <div class="bg-gradient-to-r from-purple-400 to-purple-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold">Total Revenue</h3>
                    <p class="text-3xl font-bold">$12,500</p>
                </div>
                <i class="fas fa-dollar-sign text-4xl"></i> <!-- Font Awesome Icon -->
            </div>

            <!-- Total Products Sold Card -->
            <div class="bg-gradient-to-r from-orange-400 to-orange-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold">Total Products Sold</h3>
                    <p class="text-3xl font-bold">1250 units</p>
                </div>
                <i class="fas fa-cogs text-4xl"></i> <!-- Font Awesome Icon -->
            </div>

            <!-- Total Transactions Card -->
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white p-6 rounded-lg shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold">Total Transactions</h3>
                    <p class="text-3xl font-bold">320</p>
                </div>
                <i class="fas fa-exchange-alt text-4xl"></i> <!-- Font Awesome Icon -->
            </div>
        </div>

        <!-- Second Row with Product Stock Levels and Sales Trends -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Product Stock Levels (8/12 width) -->
            <div class="col-span-8 bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-purple-600 mb-4">Product Stock Levels</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 text-left">Product Name</th>
                            <th class="py-2 text-left">Category</th>
                            <th class="py-2 text-left">Stock Available</th>
                            <th class="py-2 text-left">Low Stock Alert</th>
                            <th class="py-2 text-left">Price</th>
                            <th class="py-2 text-left">Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-2">Product A</td>
                            <td class="py-2">Electronics</td>
                            <td class="py-2">150</td>
                            <td class="py-2 px-4 border-b">
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-green-500 rounded-full">High</span>
                            </td>
                            <td class="py-2">$500</td>
                            <td class="py-2">30</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2">Product B</td>
                            <td class="py-2">Furniture</td>
                            <td class="py-2">20</td>
                            <td class="py-2 px-4 border-b">
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-yellow-500 rounded-full">Low</span>
                            </td>
                            <td class="py-2">$150</td>
                            <td class="py-2">15</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2">Product C</td>
                            <td class="py-2">Toys</td>
                            <td class="py-2">0</td>
                            <td class="py-2 px-4 border-b">
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-white bg-red-700 rounded-full">Empty</span>
                            </td>
                            <td class="py-2">$30</td>
                            <td class="py-2">5</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sales Trends Chart (4/12 width) -->
            <div class="col-span-4 bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-semibold text-purple-600 mb-4">Sales Trends (Weekly)</h3>
                <!-- Apex Chart Container -->
                <div id="chart" class="h-60 bg-gray-200 rounded-lg"></div>
            </div>
        </div>

    </div>

    <!-- Include the ApexCharts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var options = { 
            series: [{
                name: "Sales",
                data: [10, 20, 35, 50, 49, 60, 70] // Weekly sales data (example)
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Sales Trends by Week',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // Alternating row colors
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], // Weekly labels
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

</x-app-layout>
