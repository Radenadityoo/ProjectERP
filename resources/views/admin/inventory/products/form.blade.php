@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">{{ $product ? 'Edit Product' : 'Create Product' }}</h1>
            <a href="{{ route('admin.inventory.products.index') }}" class="px-3 py-2 border rounded">Back</a>
        </div>

        <form method="post" action="{{ $product ? route('admin.inventory.products.update', $product) : route('admin.inventory.products.store') }}">
            @csrf
            @if($product)
                @method('put')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm">Name</label>
                    <input name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border rounded p-2 bg-white dark:bg-gray-900" />
                </div>
                <div>
                    <label class="block text-sm">SKU</label>
                    <input name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="w-full border rounded p-2 bg-white dark:bg-gray-900" />
                </div>
                <div>
                    <label class="block text-sm">Price</label>
                    <input name="price" value="{{ old('price', $product->price ?? '') }}" class="w-full border rounded p-2 bg-white dark:bg-gray-900" />
                </div>
                <div>
                    <label class="block text-sm">Quantity</label>
                    <input name="quantity" value="{{ old('quantity', $product->quantity ?? 0) }}" class="w-full border rounded p-2 bg-white dark:bg-gray-900" />
                </div>
            </div>

            <div>
                <button class="px-4 py-2 bg-erp text-white rounded">Save</button>
            </div>
        </form>
    </div>
@endsection
