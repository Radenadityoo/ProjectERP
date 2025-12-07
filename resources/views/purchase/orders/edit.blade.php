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
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 grid md:grid-cols-2 gap-4">
        <div class="space-y-3">
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Vendor</label>
                <select class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option selected>Acme Supplies</option>
                    <option>Northwind Traders</option>
                    <option>Globex</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Order Date</label>
                    <input type="date" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="2025-12-08">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Reference</label>
                    <input type="text" placeholder="Internal reference" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="REF-PO-0012">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Currency</label>
                    <select class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option selected>USD</option>
                        <option>EUR</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Status</label>
                    <div class="mt-1 inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                        Draft
                    </div>
                </div>
            </div>
        </div>
        {{-- Totals Sidebar --}}
        <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4 space-y-2">
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span>Untaxed Amount</span>
                <span class="font-semibold text-gray-900 dark:text-gray-100">$1,550.00</span>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span>Taxes</span>
                <span class="font-semibold text-gray-900 dark:text-gray-100">$155.00</span>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex items-center justify-between text-base font-bold text-gray-900 dark:text-gray-100">
                <span>Total</span>
                <span>$1,705.00</span>
            </div>
        </div>
    </div>

    {{-- Order Lines --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="p-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Order Lines</h3>
            <button class="inline-flex items-center px-3 py-2 rounded-xl bg-erp text-white hover:bg-opacity-90 text-sm font-medium" onclick="addOrderLine()">Add Product Line</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-xs uppercase text-gray-500 dark:text-gray-400 border-y border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Unit Price</th>
                        <th class="px-4 py-3">Taxes</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($lines as $line)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-4 py-3">
                                <select class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm">
                                    <option selected>{{ $line['product'] }}</option>
                                    <option>Motor Assembly</option>
                                </select>
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" value="{{ $line['desc'] }}" class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm" />
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" value="{{ $line['qty'] }}" class="w-20 px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm" />
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" value="{{ $line['price'] }}" class="w-24 px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm" />
                            </td>
                            <td class="px-4 py-3">
                                <select class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm">
                                    <option selected>{{ $line['tax'] }}</option>
                                    <option>VAT 0%</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">${{ number_format($line['subtotal'], 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <button class="text-red-600 dark:text-red-400 hover:underline text-sm" onclick="removeOrderLine(this)">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Actions --}}
    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
        <a href="{{ route('purchase.orders.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium">Cancel</a>
        <button class="px-4 py-2 rounded-xl bg-erp text-white hover:bg-opacity-90 text-sm font-semibold" onclick="savePurchaseOrder()">Save</button>
        <button class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 text-sm font-semibold" onclick="savePurchaseOrder()">Save & Confirm</button>
    </div>
</div>

@push('scripts')
<script>
    function removeOrderLine(btn) {
        const tbody = btn.closest('tbody');
        if (tbody.querySelectorAll('tr').length > 1) {
            btn.closest('tr').remove();
        }
    }

    function addOrderLine() {
        alert('Add product line (demo)');
    }

    function savePurchaseOrder() {
        alert('Purchase Order updated (demo)');
    }
</script>
@endpush
@endsection
