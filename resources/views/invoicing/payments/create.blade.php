@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Register Payment</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Record a customer payment against an invoice</p>
        </div>
        <a href="{{ route('invoicing.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Payments
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <form method="POST" action="#" class="space-y-6">
                @csrf

                {{-- Payment Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Payment Number --}}
                        <div>
                            <label for="payment_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Number *</label>
                            <input type="text" id="payment_number" name="payment_number" value="PMT/2025/0001" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Payment Date --}}
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Date *</label>
                            <input type="date" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Customer --}}
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer *</label>
                            <select id="customer_id" name="customer_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer['id'] }}">{{ $customer['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Invoice Reference --}}
                        <div>
                            <label for="invoice_ref" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Invoice Reference</label>
                            <input type="text" id="invoice_ref" name="invoice_ref" placeholder="INV/2025/0001" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional - Leave blank for prepayment</p>
                        </div>

                        {{-- Journal Entry --}}
                        <div>
                            <label for="journal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Journal *</label>
                            <select id="journal" name="journal" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Journal</option>
                                <option value="Bank - BCA">Bank - BCA</option>
                                <option value="Bank - Mandiri">Bank - Mandiri</option>
                                <option value="Cash">Cash</option>
                                <option value="Petty Cash">Petty Cash</option>
                            </select>
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method *</label>
                            <select id="payment_method" name="payment_method" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Method</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Giro">Giro</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Payment Details --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Details</h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        {{-- Payment Amount --}}
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Amount *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 dark:text-gray-400">Rp</span>
                                <input type="number" id="amount" name="amount" step="0.01" min="0" required class="w-full pl-12 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        {{-- Memo/Notes --}}
                        <div>
                            <label for="memo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Memo/Notes</label>
                            <textarea id="memo" name="memo" rows="4" placeholder="Add any notes about this payment..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('invoicing.payments.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" name="action" value="draft" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-colors duration-200">
                        Save as Draft
                    </button>
                    <button type="submit" name="action" value="post" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                        Register Payment
                    </button>
                </div>
            </form>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="space-y-6 sticky top-6">
                {{-- Payment Summary --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Draft</span>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900 dark:text-white">Total Amount</span>
                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400" id="total-display">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Tips --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-2">Quick Tips</h4>
                            <ul class="text-xs text-blue-800 dark:text-blue-400 space-y-1 list-disc list-inside">
                                <li>Link payment to invoice for automatic reconciliation</li>
                                <li>Leave invoice blank for prepayments</li>
                                <li>Draft payments can be edited before posting</li>
                                <li>Posted payments update customer balance</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Format currency
    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    }

    // Update total display
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        document.getElementById('total-display').textContent = formatRupiah(amount);
    });
</script>
@endsection
