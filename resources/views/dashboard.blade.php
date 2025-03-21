<x-app-layout>
   <x-slot name="header" class="bg-green-100">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <!-- Download Button -->
        <a 
            href="https://drive.google.com/file/d/1egrClc3GjG9ZvFWhLbiMYZb5RnewOQaf/view?usp=sharing" 
            target="_blank" 
            class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            Download APK
        </a>
    </div>
</x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4"> <!-- Added px-4 for small screens -->
            <!-- First Row: Three Cards -->
            <div class="grid grid-cols-12 gap-6 mb-6">

                <!-- Card 1: Total Stock Worth -->
                <div class="col-span-12 sm:col-span-4 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-cogs text-green-500 mr-2"></i> Total Stock Worth
                    </h3>
                    <p class="text-2xl font-bold text-gray-800">K{{ number_format($totalStockWorth, 2) }}</p>
                </div>

                <!-- Card 2: Revenue of the Day -->
                <div class="col-span-12 sm:col-span-4 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-dollar-sign text-green-500 mr-2"></i> Revenue Today
                    </h3>
                    <p class="text-2xl font-bold text-gray-800">K{{ number_format($revenueToday, 2) }}</p>
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
                <div class="col-span-12 sm:col-span-8 bg-white shadow-md rounded-md p-4 ">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-chart-line text-green-500 mr-2"></i> Sales Overview (7 Days)
                    </h3>
                    <!-- Chart Here (You can use a chart library like ApexCharts or Chart.js) -->
                    <!-- Placeholder for Chart -->
                    <div id="chart"></div>        
                </div>

                <!-- List Card (4/12) -->
                <div class="col-span-12 sm:col-span-4 bg-white shadow-md rounded-md p-4">
                    <h3 class="font-semibold text-lg text-gray-700 mb-4">
                        <i class="fa fa-boxes text-yellow-500 mr-2"></i> Top-Selling Products
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
                                    <p>Revenue: K{{ number_format($product->total_revenue, 2) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <!-- Third Row: Product Stock Table -->
            
        </div>
    </div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartOptions = {
            series: [{
                name: "Sales",
                data: @json($chartData['sales'])
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
            colors: ['#22c55e'], // Blue for Sales
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Sales Trend (Last 7 Days)',
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
            }],
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            },
            responsive: [{
                breakpoint: 1200,
                options: {
                    chart: {
                        height: 300
                    },
                    title: {
                        align: 'center'
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        }
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center'
                    }
                }
            }, {
                breakpoint: 768,
                options: {
                    chart: {
                        height: 250,
                        type: 'bar'
                    },
                    title: {
                        text: 'Sales Trend (Last 7 Days)',
                        align: 'center'
                    },
                    xaxis: {
                        labels: {
                            rotate: -90
                        }
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center'
                    }
                }
            }]
        };

        const chart = new ApexCharts(document.querySelector("#chart"), chartOptions);
        chart.render();
    });
</script>



</x-app-layout>
