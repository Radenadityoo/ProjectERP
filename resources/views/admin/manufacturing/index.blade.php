@extends('layouts.admin')

@section('title', 'Manufacturing Orders')

@section('content')
    <div class="mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Manufacturing Orders</h1>
    </div>
    <div class="mb-4">
        <a href="{{ route('admin.manufacturing.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded shadow hover:bg-opacity-90">Create MO</a>
    </div>

    <div>

        <div class="bg-white dark:bg-gray-800 shadow rounded">
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-3 py-2">Reference</th>
                                <th class="px-3 py-2">Product</th>
                                <th class="px-3 py-2">Quantity</th>
                                <th class="px-3 py-2">Deadline</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $item->reference }}</td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ optional($item->product)->name }}</td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ $item->quantity }}</td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">{{ $item->deadline ? $item->deadline->format('Y-m-d') : '—' }}</td>
                                    <td class="px-3 py-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                            @if($item->status === 'draft') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                            @elseif($item->status === 'confirmed') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                            @else bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 @endif">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.manufacturing.show', $item) }}" class="text-blue-600 dark:text-blue-400 hover:underline">View</a>
                                            <form action="{{ route('admin.manufacturing.destroy', $item) }}" method="post" class="inline" onsubmit="return confirm('Delete this MO?');">
                                                @csrf @method('delete')
                                                <button class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
