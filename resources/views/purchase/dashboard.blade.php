@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Summary Cards --}}
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total POs this month</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_pos'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">+8 vs last month</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total purchased</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($stats['total_purchased'], 0) }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">+12% MoM</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Waiting approval</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['pending_approval'] }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">pending manager</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Top vendor</div>
            <div class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">{{ $top_vendors[0]['name'] ?? 'N/A' }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">${{ number_format($top_vendors[0]['total'] ?? 0, 0) }}</div>
        </div>
    </div>

    {{-- Top Vendors Section --}}
    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Top Vendors</h3>
            <div class="space-y-3">
                @foreach($top_vendors as $vendor)
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $vendor['name'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ count($top_vendors) }} POs</div>
                        </div>
                        <div class="text-lg font-bold text-erp">${{ number_format($vendor['total'], 0) }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Purchase Trends Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Purchase Trends</h3>
                <select class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-100">
                    <option>Last 6 months</option>
                    <option>Last 12 months</option>
                </select>
            </div>
            <div class="h-48 flex items-center justify-center bg-gradient-to-b from-gray-100 to-gray-200 dark:from-gray-900 dark:to-gray-800 rounded-lg text-gray-500 dark:text-gray-400 text-sm">
                Chart placeholder (integrate with Chart.js)
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('purchase.orders.create') }}" class="px-4 py-2 rounded-xl bg-erp text-white hover:bg-opacity-90 font-medium inline-flex items-center">
                + New Purchase Order
            </a>
            <a href="{{ route('purchase.orders.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900 font-medium">
                View All Orders
            </a>
            <a href="{{ route('purchase.vendors.create') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900 font-medium">
                + New Vendor
            </a>
            <a href="{{ route('purchase.vendors.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-900 font-medium">
                View All Vendors
            </a>
        </div>
    </div>
</div>
@endsection
