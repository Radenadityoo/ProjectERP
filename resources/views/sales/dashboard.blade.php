<x-app-layout>
    <div class="min-h-screen bg-[#F7F8F7] dark:bg-gray-950">
        <div class="flex">
            <x-sidebar />
            
            <main class="flex-1 overflow-y-auto">
                @include('components.commandbar', $commandbar)
                
                <div class="p-6">
                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        {{-- Total Sales Orders --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Sales Orders</span>
                                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_orders'] }}</div>
                        </div>

                        {{-- Total Revenue --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</span>
                                <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['total_revenue'] }}</div>
                        </div>

                        {{-- Waiting Confirmation --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Waiting Confirmation</span>
                                <div class="w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['waiting_confirmation'] }}</div>
                        </div>

                        {{-- Top Customers --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Active Customers</span>
                                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['top_customers_count'] }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Sales Trend Chart Placeholder --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Sales Trend (6 Months)</h3>
                            <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-lg">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Chart visualization placeholder</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        Data: {{ implode(', ', $trend_data['labels']) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Top Customers --}}
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Top Customers</h3>
                            <div class="space-y-3">
                                @foreach($top_customers as $customer)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $customer['name'] }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $customer['orders'] }} orders</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $customer['revenue'] }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="mt-6">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Quick Actions</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <a href="{{ route('sales.orders.create') }}" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">New Sales Order</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Create order</div>
                                    </div>
                                </a>

                                <a href="{{ route('sales.customers.create') }}" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">New Customer</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Add customer</div>
                                    </div>
                                </a>

                                <a href="{{ route('sales.orders.index') }}" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">View All Orders</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Browse orders</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
