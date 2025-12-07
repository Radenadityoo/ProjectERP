@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-4">Inventory Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <div class="text-sm text-gray-500">Products</div>
                <div class="text-2xl font-bold">{{ $productsCount }}</div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <div class="text-sm text-gray-500">Movements Today</div>
                <div class="text-2xl font-bold">{{ $stockMovementsToday }}</div>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                <div class="text-sm text-gray-500">Low Stock (&lt;10)</div>
                <div class="text-2xl font-bold text-erp">{{ $lowStock }}</div>
            </div>
        </div>

        <div class="flex gap-3 mb-4">
            <a href="{{ route('admin.inventory.products.index') }}" class="px-4 py-2 bg-erp text-white rounded">Products</a>
            <a href="{{ route('admin.inventory.receipts.index') }}" class="px-4 py-2 border rounded">Receive</a>
            <a href="{{ route('admin.inventory.deliveries.index') }}" class="px-4 py-2 border rounded">Deliver</a>
            <a href="{{ route('admin.inventory.transfers.index') }}" class="px-4 py-2 border rounded">Transfers</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            <h2 class="font-medium mb-2">Recent Movements</h2>
            @include('admin.inventory.movements.partials.list', ['items' => \App\Models\StockMovement::with('product')->orderBy('created_at','desc')->limit(8)->get()])
        </div>
    </div>
@endsection
