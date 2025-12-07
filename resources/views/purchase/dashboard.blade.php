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
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($stats['total_purchased'], 0) }}</div>
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
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">${{ number_format($top_vendors[0]['total'], 0) }}</div>
        </div>
    </div>

    {{-- Purchase Trends --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Purchase Trends</h3>
            <select class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-100">
                <option>Last 6 months</option>
                <option>Last 12 months</option>
            </select>
        </div>
        <div class="h-56 flex items-center justify-center text-gray-500 dark:text-gray-400 text-sm">
            <div class="w-full h-40 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-xl flex items-center justify-center">
                Chart placeholder (integrate with Chart.js or ApexCharts)
            </div>
        </div>
    </div>

    {{-- Top Vendors & Recent Orders --}}
    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Top Vendors</h3>
            <div class="space-y-3">
                @foreach($top_vendors as $vendor)
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $vendor['name'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $vendor['total'] }} orders</div>
                        </div>
                        <div class="text-right font-semibold text-gray-900 dark:text-gray-100">${{ number_format($vendor['total'], 0) }}</div>
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
