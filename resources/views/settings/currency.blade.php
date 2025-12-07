@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Currency Settings</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Default currency and exchange rates</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4" data-auto-hide="2500">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
        <form method="POST" action="{{ route('settings.currency.update') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Currency</label>
                <select name="default" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    @foreach(['IDR','USD','EUR'] as $cur)
                        <option value="{{ $cur }}" @selected(old('default', $currency['default'] ?? 'IDR')===$cur)>{{ $cur }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">USD → IDR</label>
                    <input type="number" step="0.0001" name="USD_IDR" value="{{ old('USD_IDR', $currency['rates']['USD_IDR'] ?? 15500) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">EUR → IDR</label>
                    <input type="number" step="0.0001" name="EUR_IDR" value="{{ old('EUR_IDR', $currency['rates']['EUR_IDR'] ?? 17000) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">USD → EUR</label>
                    <input type="number" step="0.0001" name="USD_EUR" value="{{ old('USD_EUR', $currency['rates']['USD_EUR'] ?? 0.92) }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Save Rates</button>
        </form>
    </div>
</div>
@endsection
