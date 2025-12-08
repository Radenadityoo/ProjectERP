@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="mb-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Products</h1>
        </div>
        <div class="mb-4">
            <a href="{{ route('admin.inventory.products.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded hover:bg-opacity-90">Create Product</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">

            <!-- Table View -->
            <div id="tableView" style="display: none;">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase">
                            <tr>
                                <th class="px-3 py-2">Name</th>
                                <th class="px-3 py-2">SKU</th>
                                <th class="px-3 py-2">Price</th>
                                <th class="px-3 py-2">Quantity</th>
                                <th class="px-3 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 text-gray-900 dark:text-gray-100">
                                    <td class="px-3 py-2">{{ $item->name }}</td>
                                    <td class="px-3 py-2">{{ $item->sku ?? 'N/A' }}</td>
                                    <td class="px-3 py-2">{{ $item->price ? currency($item->price, $defaultCurrency ?? 'IDR') : 'N/A' }}</td>
                                    <td class="px-3 py-2">{{ $item->quantity ?? 0 }}</td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.inventory.products.edit', $item) }}" class="text-[#5A8E74] hover:text-[#4a7a64]" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.inventory.products.destroy', $item) }}" method="post" class="inline" onsubmit="return confirm('Delete product?');">
                                                @csrf @method('delete')
                                                <button class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card View -->
            <div id="cardView" style="display: none;">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($items as $item)
                        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <div class="mb-3">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $item->name }}">{{ $item->name }}</h3>
                                @if($item->sku)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ $item->sku }}</p>
                                @endif
                            </div>
                            <div class="space-y-1 mb-3">
                                @if($item->price)
                                    <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Price:</span> {{ currency($item->price, $defaultCurrency ?? 'IDR') }}</p>
                                @endif
                                <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Quantity:</span> {{ $item->quantity ?? 0 }}</p>
                                @if($item->category)
                                    <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium">Category:</span> {{ $item->category }}</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.inventory.products.edit', $item) }}" class="flex-1 text-center px-3 py-1.5 bg-erp text-white text-sm rounded hover:bg-opacity-90">Edit</a>
                                <form action="{{ route('admin.inventory.products.destroy', $item) }}" method="post" class="flex-1" onsubmit="return confirm('Delete product?');">
                                    @csrf @method('delete')
                                    <button class="w-full px-3 py-1.5 bg-red-600 text-white text-sm rounded hover:bg-red-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Initialize view mode from localStorage or default to table
    const viewMode = localStorage.getItem('productViewMode') || 'table';
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    
    if (viewMode === 'cards') {
        cardView.style.display = 'block';
        tableView.style.display = 'none';
    } else {
        tableView.style.display = 'block';
        cardView.style.display = 'none';
    }
</script>
@endpush
