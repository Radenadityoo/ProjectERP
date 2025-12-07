@extends('layouts.admin')

@section('content')
@php
    $statusColors = [
        'Draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200',
        'Waiting' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100',
        'Purchase' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
        'Received' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100',
    ];
@endphp

<div class="p-6 space-y-6">
    {{-- Header & Filters --}}
    <div class="flex flex-col gap-4">
        <div class="mb-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Purchase Orders</h1>
        </div>
        <div class="mb-4">
            <a href="{{ route('purchase.orders.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded-xl shadow hover:bg-opacity-90 font-medium">
                + New Purchase Order
            </a>
        </div>

        <div class="flex flex-col md:flex-row gap-3 w-full">
            <input type="search" placeholder="Search PO number or vendor…" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" />
            <select class="w-full md:w-40 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <option>Status (All)</option>
                <option>Draft</option>
                <option>Waiting</option>
                <option>Purchase</option>
                <option>Received</option>
            </select>
            <select class="w-full md:w-48 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <option>Vendor (All)</option>
                <option>Acme Supplies</option>
                <option>Northwind Traders</option>
                <option>Globex</option>
                <option>Innotech</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-xs uppercase text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3">PO Number</th>
                        <th class="px-4 py-3">Vendor</th>
                        <th class="px-4 py-3">Order Date</th>
                        <th class="px-4 py-3">Expected Arrival</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Total Amount</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($items as $po)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $po['number'] }}</td>
                            <td class="px-4 py-3 text-gray-800 dark:text-gray-200">{{ $po['vendor'] }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $po['order_date'] }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $po['arrival'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$po['status']] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $po['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">${{ number_format($po['total'], 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-3 text-sm">
                                    <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">View</a>
                                    <a href="{{ route('purchase.orders.edit', 1) }}" class="text-erp hover:underline">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
            <span>Showing 1-{{ count($items) }} of 24</span>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Prev</button>
                <button class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-erp text-white">1</button>
                <button class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">2</button>
                <button class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection
