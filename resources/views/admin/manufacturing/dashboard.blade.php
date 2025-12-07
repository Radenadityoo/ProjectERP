@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manufacturing Dashboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Production pulse across recent manufacturing orders</p>
        </div>
        <a href="{{ route('admin.manufacturing.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded-lg shadow hover:bg-opacity-90 text-sm font-medium">
            Create MO
        </a>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total MOs</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_mos'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">All time created</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">In Progress</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['in_progress'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Draft or confirmed</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Completed</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['completed_mos'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Status done</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">Late</div>
            <div class="mt-2 text-3xl font-bold text-erp">{{ $stats['late_mos'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Past deadline & not done</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Trend Chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">MO Creation Trend</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Last 6 months</p>
                </div>
            </div>
            <div class="relative" style="height: 320px;">
                <canvas id="moTrendChart"></canvas>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h3>
            <a href="{{ route('admin.manufacturing.create') }}" class="block px-4 py-3 rounded-lg bg-erp text-white text-sm font-medium text-center hover:bg-opacity-90">New Manufacturing Order</a>
            <a href="{{ route('admin.manufacturing.index') }}" class="block px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-800 dark:text-gray-100 text-center hover:bg-gray-50 dark:hover:bg-gray-900">View All Orders</a>
            <a href="{{ route('admin.inventory.products.index') }}" class="block px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 text-sm font-medium text-gray-800 dark:text-gray-100 text-center hover:bg-gray-50 dark:hover:bg-gray-900">Check Component Stock</a>
        </div>
    </div>

    {{-- Recent MOs --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Manufacturing Orders</h3>
            <a href="{{ route('admin.manufacturing.index') }}" class="text-sm text-erp hover:underline">Go to list</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-3 py-2">Reference</th>
                        <th class="px-3 py-2">Product</th>
                        <th class="px-3 py-2">Qty</th>
                        <th class="px-3 py-2">Deadline</th>
                        <th class="px-3 py-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recent_mos as $mo)
                        @php
                            $pill = [
                                'draft' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                                'confirmed' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                                'done' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                            ][$mo->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $mo->reference }}</td>
                            <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ optional($mo->product)->name ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ $mo->quantity ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ $mo->deadline ? $mo->deadline->format('Y-m-d') : '—' }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $pill }}">{{ ucfirst($mo->status) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-3 text-center text-gray-500 dark:text-gray-400">No manufacturing orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    const moCtx = document.getElementById('moTrendChart').getContext('2d');

    fetch('{{ route("admin.manufacturing.dashboard.trends-json") }}')
        .then(response => response.json())
        .then(data => {
            new Chart(moCtx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'MOs Created',
                        data: data.data,
                        borderColor: '#5A8E74',
                        backgroundColor: 'rgba(90, 142, 116, 0.12)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 4,
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
                                precision: 0,
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
