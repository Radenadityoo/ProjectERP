@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header & Filters --}}
    <div class="flex flex-col gap-4">
        <div class="mb-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Vendors</h1>
        </div>
        <div class="mb-4">
            <a href="{{ route('purchase.vendors.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded-xl shadow hover:bg-opacity-90 font-medium">
                + New Vendor
            </a>
        </div>

        <div class="flex flex-col md:flex-row gap-3 w-full">
            <input type="search" placeholder="Search vendors…" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" />
            <select class="w-full md:w-48 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <option>Payment Terms</option>
                <option>15 days</option>
                <option>30 days</option>
                <option>45 days</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
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
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $vendor['name'] }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vendor['email'] }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vendor['phone'] }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $vendor['terms'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($vendor['tags'] as $tag)
                                        <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">${{ number_format($vendor['spend'], 0) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-3 text-sm">
                                    <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">View</a>
                                    <a href="{{ route('purchase.vendors.edit', $vendor['id']) }}" class="text-erp hover:underline">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400">
            Showing 1-{{ count($vendors) }} of 12
        </div>
    </div>
</div>
@endsection
