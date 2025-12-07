@extends('layouts.admin')

@section('content')
<div class="space-y-6">
                    {{-- Header with Create Button --}}
                    <div class="mb-6">
                        <a href="{{ route('sales.orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#5A8E74] hover:bg-[#4a7a64] text-white rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Sales Order
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 mb-6 border border-gray-100 dark:border-gray-700">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <input type="text" placeholder="Search by SO number or customer..." class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                            </div>
                            <div>
                                <select class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                    <option value="">All Customers</option>
                                    <option value="1">PT Maju Jaya</option>
                                    <option value="2">CV Sentosa Makmur</option>
                                    <option value="3">UD Berkah Sejahtera</option>
                                </select>
                            </div>
                            <div>
                                <select class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                    <option value="">All Statuses</option>
                                    <option value="draft">Draft</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Orders Table --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">SO Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Delivery Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order['so_number'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $order['customer'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order['order_date'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order['delivery_date'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'draft' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300',
                                                    'confirmed' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                                    'delivered' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                                                    'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                                ];
                                                $colorClass = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colorClass }}">
                                                {{ ucfirst($order['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order['total'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('sales.orders.edit', $order['id']) }}" class="text-[#5A8E74] hover:text-[#4a7a64]">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
