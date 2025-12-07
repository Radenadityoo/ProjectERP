@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 grid md:grid-cols-2 gap-4">
        <div class="space-y-3">
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Vendor</label>
                <select name="vendor_id" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option value="">Select vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Order Date</label>
                    <input type="date" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="2025-12-08">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Reference</label>
                    <input type="text" placeholder="Internal reference" class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="REF-PO-NEW">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Currency</label>
                    <select class="w-full mt-1 px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                        <option selected>IDR</option>
                        <option>USD</option>
                        <option>EUR</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Status</label>
                    <div class="mt-1 inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                        New
                    </div>
                </div>
            </div>
        </div>
        {{-- Totals Sidebar --}}
        <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4 space-y-2">
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span>Untaxed Amount</span>
                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ currency(0) }}</span>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span>Taxes</span>
                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ currency(0) }}</span>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex items-center justify-between text-base font-bold text-gray-900 dark:text-gray-100">
                <span>Total</span>
                <span>{{ currency(0) }}</span>
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
            <table class="min-w-full text-sm" id="orderLines">
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
                <tbody id="orderLineBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 order-line">
                        <td class="px-4 py-3">
                            <select name="product_id[]" class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm">
                                <option value="">Select product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" placeholder="Description" class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm" />
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" value="1" class="w-20 px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm qty-input" onchange="calculateSubtotal(this)" />
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" value="0" class="w-24 px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm price-input" onchange="calculateSubtotal(this)" />
                        </td>
                        <td class="px-4 py-3">
                            <select class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm">
                                <option>VAT 0%</option>
                                <option>VAT 10%</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100 subtotal">{{ currency(0) }}</td>
                        <td class="px-4 py-3 text-right">
                            <button class="text-red-600 dark:text-red-400 hover:underline text-sm" onclick="removeOrderLine(this)">Remove</button>
                        </td>
                    </tr>
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
    function addOrderLine() {
        const tbody = document.getElementById('orderLineBody');
        const newRow = tbody.querySelector('.order-line').cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(el => {
            if (el.classList.contains('qty-input')) el.value = 1;
            else if (el.classList.contains('price-input')) el.value = 0;
            else if (el.tagName === 'INPUT') el.value = '';
            else el.value = el.options[0].value;
        });
        tbody.appendChild(newRow);
    }

    function removeOrderLine(btn) {
        const tbody = document.getElementById('orderLineBody');
        if (tbody.querySelectorAll('.order-line').length > 1) {
            btn.closest('tr').remove();
            calculateTotals();
        }
    }

    const currencyPrefix = '{{ currency(0) }}'.replace(/0+(?:[.,]0+)?$/, '').trim() + ' ';

    function calculateSubtotal(el) {
        const row = el.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const subtotal = qty * price;
        row.querySelector('.subtotal').textContent = currencyPrefix + subtotal.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        calculateTotals();
    }

    function calculateTotals() {
        let total = 0;
        document.querySelectorAll('.order-line').forEach(row => {
            const raw = row.querySelector('.subtotal').textContent;
            const numeric = parseFloat(raw.replace(/[^0-9.,-]/g, '').replace(/,/g, '.')) || 0;
            total += numeric;
        });
        // Update totals sidebar (demo - would need actual implementation)
    }

    function savePurchaseOrder() {
        alert('Purchase Order saved (demo)');
    }
</script>
@endpush
@endsection
