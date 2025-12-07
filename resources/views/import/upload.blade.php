@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Products & BoM</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload a CSV to import products and bill of materials</p>
        </div>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg">Back</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-4">
            <p class="font-semibold mb-2">Please fix the following:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4" data-auto-hide="3000">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-4">
        <form method="POST" action="{{ route('import.process') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CSV File</label>
                <input type="file" name="file" accept=".csv" required class="w-full text-sm text-gray-700 dark:text-gray-200">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Columns: product_name, sku, category, cost, price, uom, bom_name, product_sku, component_sku, component_qty, unit_cost</p>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Import</button>
        </form>
    </div>
</div>
@endsection
