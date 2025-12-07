@props(['product'])

<div class="inline-block w-[260px] h-[180px] rounded-[12px] border border-gray-200 bg-white p-4 flex flex-col justify-between hover:shadow-sm transition">
    <div>
        <div class="text-sm text-gray-500">SKU: {{ $product->sku ?? '—' }}</div>
        <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
        <div class="text-sm text-gray-500 mt-2">{{ \Illuminate\Support\Str::limit($product->description ?? 'No description', 80) }}</div>
    </div>

    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-600 font-medium">${{ number_format($product->price ?? 0,2) }}</div>
        <a href="{{ route('products.edit', $product->id) }}" class="text-sm text-erp">Edit</a>
    </div>
</div>
