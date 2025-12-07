@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    <div class="mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Vendors</h1>
    </div>
    <div class="mb-4">
        <a href="{{ route('purchase.vendors.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded-xl shadow hover:bg-opacity-90 font-medium">+ New Vendor</a>
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <input type="search" placeholder="Search vendors…" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" />
        <select class="px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            <option>Payment Terms</option>
            <option>15 days</option>
            <option>30 days</option>
            <option>45 days</option>
        </select>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-xs uppercase text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3">Vendor Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Payment Terms</th>
                        <th class="px-4 py-3">Tags</th>
                        <th class="px-4 py-3 text-right">Total Spend</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($vendors as $vendor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $vendor->name }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vendor->email }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vendor->phone }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vendor->payment_terms }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach(($vendor->tags ?? []) as $tag)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">{{ currency($vendor->total_spend, 'IDR') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <a href="{{ route('purchase.vendors.edit', $vendor->id) }}" class="text-[#5A8E74] hover:text-[#4a7a64]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
