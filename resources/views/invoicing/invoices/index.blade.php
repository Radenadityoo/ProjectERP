@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    {{-- Header with Create Button --}}
    <div>
        <a href="{{ route('invoicing.invoices.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#5A8E74] hover:bg-[#4a7a64] text-white rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Invoice
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                    <input type="text" id="invoice-search" placeholder="Search by invoice number or customer..." class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
            </div>
            <div>
                    <select id="status-filter" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="posted">Posted</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div>
                <select id="customer-filter" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                    <option value="">All Customers</option>
                    <option value="1">PT Maju Jaya</option>
                    <option value="2">CV Sentosa Makmur</option>
                    <option value="3">UD Berkah Sejahtera</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Invoices Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700" id="invoices-tbody">
                    @foreach($invoices as $invoice)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 invoice-row" data-invoice="{{ $invoice['number'] }}" data-customer="{{ $invoice['customer'] }}" data-status="{{ strtolower($invoice['status']) }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $invoice['number'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $invoice['customer'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice['invoice_date'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice['due_date'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300',
                                    'posted' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                                    'paid' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                                    'overdue' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                ];
                                $colorClass = $statusColors[strtolower($invoice['status'])] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300';
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colorClass }}">
                                {{ ucfirst($invoice['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $invoice['total'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('invoicing.invoices.edit', $invoice['id']) }}" class="text-[#5A8E74] hover:text-[#4a7a64]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
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

    @push('scripts')
    <script>
        (function() {
            const searchInput = document.getElementById('invoice-search');
            const statusFilter = document.getElementById('status-filter');
            const customerFilter = document.getElementById('customer-filter');
            const rows = document.querySelectorAll('.invoice-row');

            function filterRows() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();
                const customerValue = customerFilter.value.toLowerCase();

                rows.forEach(row => {
                    const invoice = row.dataset.invoice.toLowerCase();
                    const customer = row.dataset.customer.toLowerCase();
                    const status = row.dataset.status.toLowerCase();

                    const matchesSearch = invoice.includes(searchTerm) || customer.includes(searchTerm);
                    const matchesStatus = !statusValue || status === statusValue;
                    const matchesCustomer = !customerValue || customer.includes(customerValue);

                    row.style.display = matchesSearch && matchesStatus && matchesCustomer ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterRows);
            statusFilter.addEventListener('change', filterRows);
            customerFilter.addEventListener('change', filterRows);
        })();
    </script>
    @endpush
@endsection
