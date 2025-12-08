@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Payment</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update payment details</p>
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
            <form method="POST" action="{{ route('invoicing.payments.update', $payment->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Payment Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Payment Number --}}
                        <div>
                            <label for="payment_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Number *</label>
                            <input type="text" id="payment_number" name="payment_number" value="{{ $payment->payment_number }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Payment Date --}}
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Date *</label>
                            <input type="date" id="payment_date" name="payment_date" value="{{ $payment->payment_date->format('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Customer --}}
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Customer *</label>
                            <select id="customer_id" name="customer_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer['id'] }}" @if($customer['id'] == $payment->customer_id) selected @endif>{{ $customer['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Invoice Reference --}}
                        <div>
                            <label for="invoice_ref" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Invoice Reference</label>
                            <input type="text" id="invoice_ref" name="invoice_ref" value="{{ $payment->invoice_ref }}" placeholder="INV/2025/0001" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Journal Entry --}}
                        <div>
                            <label for="journal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Journal *</label>
                            <select id="journal" name="journal" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Journal</option>
                                <option value="Bank - BCA" @if($payment->journal == 'Bank - BCA') selected @endif>Bank - BCA</option>
                                <option value="Bank - Mandiri" @if($payment->journal == 'Bank - Mandiri') selected @endif>Bank - Mandiri</option>
                                <option value="Cash" @if($payment->journal == 'Cash') selected @endif>Cash</option>
                                <option value="Petty Cash" @if($payment->journal == 'Petty Cash') selected @endif>Petty Cash</option>
                            </select>
                        </div>

                        {{-- Payment Method --}}
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method *</label>
                            <select id="payment_method" name="payment_method" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Method</option>
                                <option value="bank_transfer" @if($payment->payment_method == 'bank_transfer') selected @endif>Bank Transfer</option>
                                <option value="cash" @if($payment->payment_method == 'cash') selected @endif>Cash</option>
                                <option value="credit_card" @if($payment->payment_method == 'credit_card') selected @endif>Credit Card</option>
                                <option value="check" @if($payment->payment_method == 'check') selected @endif>Check</option>
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
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 dark:text-gray-400">{{ base_currency() }}</span>
                                <input type="number" id="amount" name="amount" value="{{ $payment->amount }}" step="0.01" min="0" required class="w-full pl-16 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        {{-- Currency Code --}}
                        <div>
                            <label for="currency_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                            <input type="text" id="currency_code" name="currency_code" value="{{ $payment->currency_code ?? 'IDR' }}" maxlength="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Memo/Notes --}}
                        <div>
                            <label for="memo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Memo/Notes</label>
                            <textarea id="memo" name="memo" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $payment->memo }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                        Update Payment
                    </button>
                    <a href="{{ route('invoicing.payments.index') }}" class="flex-1 px-6 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-colors duration-200 text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-6">
            {{-- Current Values --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Current Details</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Current Amount</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ currency($payment->amount_base, base_currency()) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-sm font-medium mt-1">
                            @if($payment->status === 'posted')
                                <span class="inline-block px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Posted</span>
                            @else
                                <span class="inline-block px-2 py-1 rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
