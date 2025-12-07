@extends('layouts.admin')

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 grid md:grid-cols-3 gap-4">
        <div class="space-y-3">
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Vendor</label>
                <select class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option>Select Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor['id'] }}">{{ $vendor['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Order Date</label>
                    <input type="date" class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="2025-12-08">
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-400">Reference</label>
                    <input type="text" placeholder="Internal reference" class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="REF-PO-NEW">
                </div>
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Currency</label>
                <select class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option>USD</option>
                    <option>EUR</option>
                    <option>IDR</option>
                </select>
            </div>
        </div>

        {{-- Totals Sidebar --}}
        <div class="md:col-span-2 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-2">
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span>Untaxed Amount</span>
                <span class="font-semibold text-gray-900 dark:text-gray-100" id="untaxed">$0.00</span>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span>Taxes</span>
                <span class="font-semibold text-gray-900 dark:text-gray-100" id="taxes">$0.00</span>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex items-center justify-between text-base font-bold text-gray-900 dark:text-gray-100">
                <span>Total</span>
                <span id="total">$0.00</span>
            </div>
        </div>
    </div>

    {{-- Order Lines --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="p-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Order Lines</h3>
            <button type="button" onclick="addLine()" class="inline-flex items-center px-3 py-2 rounded-lg bg-erp text-white hover:bg-opacity-90 text-sm font-medium">
                + Add Product Line
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm" id="linesTable">
                <thead class="text-left text-xs uppercase text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
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
                <tbody id="lineRows" class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 line-row">
                        <td class="px-4 py-3">
                            <select class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 product-select" onchange="updateLine(this)">
                                <option>Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product['id'] }}" data-name="{{ $product['name'] }}">{{ $product['name'] }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 description" placeholder="Description">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" class="w-20 px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 qty" value="1" onchange="updateLine(this)">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" class="w-28 px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 price" value="0" onchange="updateLine(this)">
                        </td>
                        <td class="px-4 py-3">
                            <select class="w-full px-2 py-1.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 tax">
                                <option value="0">No Tax</option>
                                <option value="10" selected>VAT 10%</option>
                                <option value="15">VAT 15%</option>
                            </select>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100 subtotal">$0.00</td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" onclick="removeLine(this)" class="text-red-600 dark:text-red-400 hover:underline text-sm">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Actions --}}
    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
        <a href="{{ route('purchase.orders.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium text-center">Cancel</a>
        <button type="button" onclick="alert('Demo: Save functionality would be implemented here')" class="px-4 py-2 rounded-lg bg-erp text-white hover:bg-opacity-90 text-sm font-semibold">Save</button>
        <button type="button" onclick="alert('Demo: Save & Confirm functionality would be implemented here')" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-semibold">Save & Confirm</button>
    </div>
</div>

<script>
function addLine() {
    const tbody = document.getElementById('lineRows');
    const newRow = tbody.querySelector('tr').cloneNode(true);
    
    // Reset inputs
    newRow.querySelector('.product-select').value = '';
    newRow.querySelector('.description').value = '';
    newRow.querySelector('.qty').value = '1';
    newRow.querySelector('.price').value = '0';
    newRow.querySelector('.tax').value = '10';
    newRow.querySelector('.subtotal').textContent = '$0.00';
    
    tbody.appendChild(newRow);
    
    // Reattach event listeners
    newRow.querySelector('.product-select').onchange = function() { updateLine(this); };
    newRow.querySelector('.qty').onchange = function() { updateLine(this); };
    newRow.querySelector('.price').onchange = function() { updateLine(this); };
    newRow.querySelector('.tax').onchange = function() { updateLine(this); };
}

function updateLine(el) {
    const row = el.closest('tr');
    const qty = parseFloat(row.querySelector('.qty').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    const taxRate = parseFloat(row.querySelector('.tax').value) || 0;
    
    const subtotal = qty * price * (1 + taxRate / 100);
    row.querySelector('.subtotal').textContent = '$' + subtotal.toFixed(2);
    
    calculateTotals();
}

function removeLine(el) {
    el.closest('tr').remove();
    calculateTotals();
}

function calculateTotals() {
    let untaxed = 0;
    let totalTax = 0;
    
    document.querySelectorAll('.line-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const price = parseFloat(row.querySelector('.price').value) || 0;
        const taxRate = parseFloat(row.querySelector('.tax').value) || 0;
        
        const lineUntaxed = qty * price;
        const lineTax = lineUntaxed * (taxRate / 100);
        
        untaxed += lineUntaxed;
        totalTax += lineTax;
    });
    
    document.getElementById('untaxed').textContent = '$' + untaxed.toFixed(2);
    document.getElementById('taxes').textContent = '$' + totalTax.toFixed(2);
    document.getElementById('total').textContent = '$' + (untaxed + totalTax).toFixed(2);
}

// Initial calculation on page load
document.addEventListener('DOMContentLoaded', calculateTotals);
</script>
@endsection
