@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Vendor Name</label>
                <input type="text" class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ $vendor['name'] }}">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Email</label>
                <input type="email" class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ $vendor['email'] }}">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Phone</label>
                <input type="text" class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ $vendor['phone'] }}">
            </div>
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-400">Payment Terms</label>
                <select class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <option>15 days</option>
                    <option @if($vendor['terms'] == '30 days') selected @endif>30 days</option>
                    <option>45 days</option>
                </select>
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-400">Address</label>
            <textarea rows="2" class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">{{ $vendor['address'] }}</textarea>
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-400">Tags</label>
            <select multiple class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                @foreach(['Preferred', 'Hardware', 'Electronics', 'Overseas'] as $tag)
                    <option @if(in_array($tag, $vendor['tags'])) selected @endif>{{ $tag }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hold Ctrl/Cmd to select multiple.</p>
        </div>

        <div>
            <label class="text-sm text-gray-600 dark:text-gray-400">Notes</label>
            <textarea rows="3" class="w-full mt-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">{{ $vendor['notes'] }}</textarea>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('purchase.vendors.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium">Cancel</a>
        <button type="button" onclick="alert('Demo: Vendor updated successfully!')" class="px-4 py-2 rounded-lg bg-erp text-white hover:bg-opacity-90 text-sm font-semibold">Save</button>
    </div>
</div>
@endsection
