@extends('layouts.admin')

@section('content')
@php
    $commandbar = [
        'title' => 'Purchase Dashboard',
        'count' => 0,
        'showViewSwitch' => false,
    ];
@endphp

<div class="p-6 space-y-6">
    {{-- Summary Cards --}}
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total POs this month</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_pos'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">+8 vs last month</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total purchased</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ currency($stats['total_purchased'], 'IDR') }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">+12% MoM</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="text-sm text-gray-500 dark:text-gray-400">Waiting approval</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['pending_approval'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">pending manager</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="text-sm text-gray-500 dark:text-gray-400">Top vendor</div>
            <div class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">{{ $top_vendors[0]['name'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ currency($top_vendors[0]['total'], 'IDR') }}</div>
        </div>
    </div>

    {{-- Purchase Trends --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Purchase Trends</h3>
            <select id="trendsTimeframe" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-100">
                <option value="6">Last 6 months</option>
                <option value="12">Last 12 months</option>
            </select>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="purchaseTrendsChart"></canvas>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        const ctx = document.getElementById('purchaseTrendsChart').getContext('2d');
        let purchaseTrendsChart = null;

        function loadChart(timeframe = 6) {
            fetch('{{ route("purchase.dashboard.trends-json") }}?months=' + timeframe)
                .then(response => response.json())
                .then(data => {
                    if (purchaseTrendsChart) {
                        purchaseTrendsChart.destroy();
                    }

                    purchaseTrendsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Purchase Amount (IDR)',
                                data: data.data,
                                borderColor: '#5A8E74',
                                backgroundColor: 'rgba(90, 142, 116, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointBackgroundColor: '#5A8E74',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#1f2937',
                                        font: { size: 12 }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#1f2937',
                                        callback: function(value) {
                                            return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                                        }
                                    },
                                    grid: {
                                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#1f2937'
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                });
        }

        document.getElementById('trendsTimeframe').addEventListener('change', function() {
            loadChart(this.value);
        });

        // Load initial chart
        loadChart(6);
    </script>
    @endpush

    {{-- Top Vendors & Recent Orders --}}
    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Top Vendors</h3>
            <div class="space-y-3">
                @foreach($top_vendors as $vendor)
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $vendor['name'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Top supplier</div>
                        </div>
                        <div class="text-right font-semibold text-gray-900 dark:text-gray-100">{{ currency($vendor['total'], 'IDR') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('purchase.orders.create') }}" class="block w-full px-4 py-2 rounded-lg bg-erp text-white text-center hover:bg-opacity-90 text-sm font-medium">
                    Create Purchase Order
                </a>
                <a href="{{ route('purchase.vendors.create') }}" class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-center hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium">
                    Add New Vendor
                </a>
                <a href="{{ route('purchase.orders.index') }}" class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-center hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium">
                    View All Orders
                </a>
                <a href="{{ route('purchase.vendors.index') }}" class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-center hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium">
                    View All Vendors
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
