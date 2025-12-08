@extends('layouts.admin')

@php
    /** @var \App\Models\BomHeader $bom */
@endphp

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.bom.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">← Back to BOMs</a>
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $bom->name }}</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.bom.edit', $bom) }}" class="px-4 py-2 bg-erp text-white rounded hover:opacity-90">Edit</a>
                <button onclick="window.print()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Print / PDF</button>
            </div>
        </div>

        {{-- Overview Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Product</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $bom->product ? $bom->product->name : 'N/A' }}
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Quantity</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $bom->quantity }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Cost</div>
                    <div class="text-lg font-semibold text-green-600 dark:text-green-400 mt-1">{{ currency($bom->total_cost, $defaultCurrency ?? 'IDR') }}</div>
                </div>
            </div>
        </div>

        {{-- Components Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Components</h2>

            @if($bom->components && $bom->components->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs font-medium text-gray-600 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-4 py-3">Product</th>
                                <th class="px-4 py-3 text-right">Quantity</th>
                                <th class="px-4 py-3 text-right">Unit Cost</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($bom->components as $component)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        {{ $component->componentProduct ? $component->componentProduct->name : 'Unknown Product' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ number_format($component->qty, 2) }}</td>
                                    <td class="px-4 py-3 text-right text-gray-900 dark:text-gray-100">{{ currency($component->unit_cost, $defaultCurrency ?? 'IDR') }}</td>
                                    <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-gray-100">{{ currency($component->subtotal, $defaultCurrency ?? 'IDR') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-300 dark:border-gray-600">
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">Total Cost:</td>
                                <td class="px-4 py-3 text-right font-bold text-lg text-green-600 dark:text-green-400">{{ currency($bom->total_cost, $defaultCurrency ?? 'IDR') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p>No components found for this BOM.</p>
                </div>
            @endif
        </div>
    </div>

    <style media="print">
        @page {
            margin: 1cm;
        }
        body {
            background: white;
        }
        .no-print {
            display: none;
        }
    </style>
@endsection
