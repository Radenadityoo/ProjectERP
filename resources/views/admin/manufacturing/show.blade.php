@extends('layouts.admin')

@php
    /** @var \App\Models\Manufacturing $mo */
@endphp

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('admin.manufacturing.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">← Back to Manufacturing Orders</a>
                <h1 class="text-3xl font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $mo->reference }}</h1>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Print / PDF</button>
            </div>
        </div>

        {{-- Overview Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Reference</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $mo->reference }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Product</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">
                        {{ $mo->product ? $mo->product->name : 'N/A' }}
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Quantity</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $mo->quantity }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</div>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($mo->status === 'draft') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                            @elseif($mo->status === 'confirmed') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                            @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @endif">
                            {{ ucfirst($mo->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deadline Info --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Deadline</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">
                        @if($mo->deadline)
                            {{ $mo->deadline->format('F j, Y') }}
                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $mo->deadline->diffForHumans() }})</span>
                        @else
                            <span class="text-gray-400">Not set</span>
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Created</div>
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $mo->created_at->format('F j, Y H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Actions</h2>
            <div class="flex gap-2 flex-wrap">
                @if($mo->status === 'draft')
                    <form action="{{ route('admin.manufacturing.updateStatus', $mo) }}" method="post" class="inline">
                        @csrf @method('patch')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="px-4 py-2 bg-yellow-600 dark:bg-yellow-700 text-white rounded hover:bg-yellow-700 dark:hover:bg-yellow-800">Confirm Order</button>
                    </form>
                @elseif($mo->status === 'confirmed')
                    <form action="{{ route('admin.manufacturing.updateStatus', $mo) }}" method="post" class="inline">
                        @csrf @method('patch')
                        <input type="hidden" name="status" value="done">
                        <button type="submit" class="px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded hover:bg-green-700 dark:hover:bg-green-800">Mark as Done</button>
                    </form>
                    <form action="{{ route('admin.manufacturing.updateStatus', $mo) }}" method="post" class="inline">
                        @csrf @method('patch')
                        <input type="hidden" name="status" value="draft">
                        <button type="submit" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Back to Draft</button>
                    </form>
                @else
                    <div class="text-green-600 dark:text-green-400 font-medium">✓ Order Completed</div>
                @endif
                
                <form action="{{ route('admin.manufacturing.destroy', $mo) }}" method="post" onsubmit="return confirm('Delete this Manufacturing Order?');" class="inline">
                    @csrf @method('delete')
                    <button type="submit" class="px-4 py-2 bg-red-600 dark:bg-red-700 text-white rounded hover:bg-red-700 dark:hover:bg-red-800">Delete</button>
                </form>
                <a href="{{ route('admin.manufacturing.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Back to List</a>
            </div>
        </div>
    </div>

    <style media="print">
        @page {
            margin: 1cm;
        }
        body {
            background: white;
        }
    </style>
@endsection
