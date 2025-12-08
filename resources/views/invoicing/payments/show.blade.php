@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Details</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $payment->payment_number }}</p>
        </div>
        <a href="{{ route('invoicing.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Payments
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Payment Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Payment Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Payment Number</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $payment->payment_number }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Payment Date</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $payment->payment_date->format('Y-m-d') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Customer</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $payment->customer?->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Invoice Reference</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $payment->invoice_ref ?? $payment->invoice?->number ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Payment Method</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ ucfirst($payment->payment_method) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Journal</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $payment->journal }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Amount --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Payment Amount</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Amount</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ currency($payment->amount, $payment->currency_code ?? 'IDR') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Amount (Base Currency)</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ currency($payment->amount_base, base_currency()) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Currency</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $payment->currency_code ?? 'IDR' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Exchange Rate</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ number_format($payment->exchange_rate, 6) }}</p>
                    </div>
                </div>
            </div>

            {{-- Additional Details --}}
            @if($payment->memo)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Memo</h2>
                <p class="text-gray-700 dark:text-gray-300">{{ $payment->memo }}</p>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Status</h3>
                
                @if($payment->status === 'posted')
                    <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-sm font-medium">
                        Posted
                    </span>
                @elseif($payment->status === 'reconciled')
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-sm font-medium">
                        Reconciled
                    </span>
                @else
                    <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 text-sm font-medium">
                        {{ ucfirst($payment->status) }}
                    </span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('invoicing.payments.edit', $payment->id) }}" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 text-center">
                        Edit Payment
                    </a>
                    
                    <form action="{{ route('invoicing.payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this payment?')" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                            Delete Payment
                        </button>
                    </form>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Timeline</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600 dark:text-gray-400">Created</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    @if($payment->updated_at != $payment->created_at)
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-gray-600 dark:text-gray-400">Last Updated</p>
                        <p class="text-gray-900 dark:text-gray-100">{{ $payment->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
