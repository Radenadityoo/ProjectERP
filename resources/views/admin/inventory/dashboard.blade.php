@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6">
        <h1 class="text-2xl font-semibold mb-4">Inventory Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <div class="text-sm text-gray-500">Products</div>
                <div class="text-2xl font-bold">{{ $productsCount }}</div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <div class="text-sm text-gray-500">Movements Today</div>
                <div class="text-2xl font-bold">{{ $stockMovementsToday }}</div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <div class="text-sm text-gray-500">Low Stock (&lt;10)</div>
                <div class="text-2xl font-bold text-erp">{{ $lowStock }}</div>
            </div>
        </div>

        {{-- Stock Movements Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Stock Movements (7 Days)</h3>
            <div class="relative" style="height: 300px;">
                <canvas id="movementsChart"></canvas>
            </div>
        </div>

        <div class="flex gap-3 mb-4">
            <a href="{{ route('admin.inventory.products.index') }}" class="px-4 py-2 bg-erp text-white rounded">Products</a>
            <a href="{{ route('admin.inventory.receipts.index') }}" class="px-4 py-2 border rounded">Receive</a>
            <a href="{{ route('admin.inventory.deliveries.index') }}" class="px-4 py-2 border rounded">Deliver</a>
            <a href="{{ route('admin.inventory.transfers.index') }}" class="px-4 py-2 border rounded">Transfers</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h2 class="font-medium mb-2">Recent Movements</h2>
            @include('admin.inventory.movements.partials.list', ['items' => \App\Models\StockMovement::with('product')->orderBy('created_at','desc')->limit(8)->get()])
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        const movCtx = document.getElementById('movementsChart').getContext('2d');
        
        fetch('{{ route("admin.inventory.dashboard.movements-json") }}')
            .then(response => response.json())
            .then(data => {
                new Chart(movCtx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Stock Movements',
                            data: data.data,
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'],
                            borderColor: ['#1e3a8a', '#065f46', '#92400e', '#7f1d1d', '#4c1d95', '#831843', '#0c4a6e'],
                            borderWidth: 1,
                            borderRadius: 4,
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
                                    color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#1f2937'
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
    </script>
    @endpush
@endsection
