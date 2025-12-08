@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-900/80 backdrop-blur overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100 dark:border-gray-800">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Unified Control Center</p>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Monitor sales, purchasing, invoicing, inventory, and fulfillment at a glance.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('sales.orders.index') }}" class="px-3 py-2 rounded-lg text-sm bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200 border border-blue-100 dark:border-blue-800">Sales</a>
                        <a href="{{ route('purchase.orders.index') }}" class="px-3 py-2 rounded-lg text-sm bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200 border border-emerald-100 dark:border-emerald-800">Purchase</a>
                        <a href="{{ route('invoicing.invoices.index') }}" class="px-3 py-2 rounded-lg text-sm bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200 border border-indigo-100 dark:border-indigo-800">Invoicing</a>
                        <a href="{{ route('admin.inventory.products.index') }}" class="px-3 py-2 rounded-lg text-sm bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200 border border-amber-100 dark:border-amber-800">Inventory</a>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @foreach($kpis as $kpi)
                    <div class="bg-white dark:bg-gray-900/80 shadow-sm rounded-xl p-4 border border-gray-100 dark:border-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $kpi['value'] }}</span>
                            <span class="text-xs px-2 py-1 rounded-full {{ $kpi['color'] }} text-white">{{ $kpi['delta'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                @foreach($chartCards as $card)
                    <div class="bg-white dark:bg-gray-900/80 shadow-sm rounded-xl p-4 border border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $card['title'] }}</p>
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">Live</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            @foreach($card['series'] as $series)
                                <div>
                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ $series['label'] }}</span>
                                        <span>{{ $series['value'] }}%</span>
                                    </div>
                                    <div class="mt-1 h-2 rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                                        <div class="h-full {{ $series['color'] }}" style="width: {{ $series['value'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">{{ $card['footer'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="bg-white dark:bg-gray-900/80 shadow-sm rounded-xl p-4 border border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Critical Reports</p>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Items needing attention</h3>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full bg-rose-50 text-rose-700 dark:bg-rose-900/40 dark:text-rose-200">Watchlist</span>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach($criticalReports as $report)
                        <a href="{{ $report['link'] }}" class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-800 hover:border-erp/60 hover:bg-[#E4EFE9] dark:hover:bg-[#163a2a] transition">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $report['label'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tap to open</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $report['count'] }}</span>
                                <span class="text-[11px] px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">{{ $report['badge'] }}</span>
                                <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
