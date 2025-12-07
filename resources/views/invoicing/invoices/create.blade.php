@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <form action="#" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Header Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Invoice Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer *</label>
                            <select name="customer_id" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Invoice Date *</label>
                            <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date *</label>
                            <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Journal *</label>
                            <select name="journal_id" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                                @foreach($journals as $journal)
                                <option value="{{ $journal['id'] }}">{{ $journal['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reference</label>
                            <input type="text" name="reference" placeholder="e.g., SO-2025-001" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#5A8E74]">
                        </div>
                    </div>
                </div>

                {{-- Invoice Lines --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Invoice Lines</h3>
                        <button type="button" id="addLineBtn" class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#5A8E74] hover:bg-[#4a7a64] text-white text-sm rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Line
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="invoiceLinesTable">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Product</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Label</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Qty</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Unit Price</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Tax %</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Subtotal</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400"></th>
                                </tr>
                            </thead>
                            <tbody id="invoiceLinesBody" class="divide-y divide-gray-100 dark:divide-gray-700">
                                {{-- Lines will be added dynamically --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Totals Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Invoice Summary</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Untaxed Amount:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100" id="untaxedAmount">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Taxes:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100" id="taxAmount">Rp 0</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900 dark:text-gray-100">Total:</span>
                                <span class="font-bold text-lg text-[#5A8E74]" id="totalAmount">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button type="submit" name="action" value="draft" class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                            Save Draft
                        </button>
                        <button type="submit" name="action" value="validate" class="w-full px-4 py-2 bg-[#5A8E74] hover:bg-[#4a7a64] text-white rounded-lg transition">
                            Validate Invoice
                        </button>
                        <a href="{{ route('invoicing.invoices.index') }}" class="block w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let lineCounter = 0;
    const products = @json($products);

    function formatRupiah(number) {
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function addInvoiceLine() {
        lineCounter++;
        const tbody = document.getElementById('invoiceLinesBody');
        const tr = document.createElement('tr');
        tr.className = 'invoice-line';
        tr.innerHTML = `
            <td class="px-3 py-2">
                <select name="lines[${lineCounter}][product_id]" class="product-select w-full px-2 py-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded text-sm text-gray-900 dark:text-gray-100" data-line="${lineCounter}">
                    <option value="">Select Product</option>
                    ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name}</option>`).join('')}
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="text" name="lines[${lineCounter}][label]" class="w-full px-2 py-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded text-sm text-gray-900 dark:text-gray-100" placeholder="Description">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="lines[${lineCounter}][quantity]" value="1" min="1" class="line-qty w-20 px-2 py-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded text-sm text-gray-900 dark:text-gray-100" data-line="${lineCounter}">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="lines[${lineCounter}][unit_price]" value="0" min="0" class="line-price w-28 px-2 py-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded text-sm text-gray-900 dark:text-gray-100" data-line="${lineCounter}">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="lines[${lineCounter}][tax]" value="11" min="0" max="100" class="line-tax w-16 px-2 py-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded text-sm text-gray-900 dark:text-gray-100" data-line="${lineCounter}">
            </td>
            <td class="px-3 py-2">
                <span class="line-subtotal font-medium text-gray-900 dark:text-gray-100" data-line="${lineCounter}">Rp 0</span>
            </td>
            <td class="px-3 py-2">
                <button type="button" class="remove-line text-red-600 dark:text-red-400 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        attachLineEvents(tr);
        calculateTotals();
    }

    function attachLineEvents(tr) {
        const productSelect = tr.querySelector('.product-select');
        const qtyInput = tr.querySelector('.line-qty');
        const priceInput = tr.querySelector('.line-price');
        const taxInput = tr.querySelector('.line-tax');
        const removeBtn = tr.querySelector('.remove-line');

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            priceInput.value = price;
            calculateTotals();
        });

        [qtyInput, priceInput, taxInput].forEach(input => {
            input.addEventListener('input', calculateTotals);
        });

        removeBtn.addEventListener('click', function() {
            tr.remove();
            calculateTotals();
        });
    }

    function calculateTotals() {
        let untaxed = 0;
        let taxes = 0;

        document.querySelectorAll('.invoice-line').forEach(line => {
            const lineNum = line.querySelector('.line-qty').getAttribute('data-line');
            const qty = parseFloat(line.querySelector('.line-qty').value) || 0;
            const price = parseFloat(line.querySelector('.line-price').value) || 0;
            const taxRate = parseFloat(line.querySelector('.line-tax').value) || 0;

            const lineSubtotal = qty * price;
            const lineTax = lineSubtotal * (taxRate / 100);

            line.querySelector(`.line-subtotal[data-line="${lineNum}"]`).textContent = formatRupiah(lineSubtotal);

            untaxed += lineSubtotal;
            taxes += lineTax;
        });

        document.getElementById('untaxedAmount').textContent = formatRupiah(untaxed);
        document.getElementById('taxAmount').textContent = formatRupiah(taxes);
        document.getElementById('totalAmount').textContent = formatRupiah(untaxed + taxes);
    }

    document.getElementById('addLineBtn').addEventListener('click', addInvoiceLine);

    // Add initial line
    addInvoiceLine();
</script>
@endsection
