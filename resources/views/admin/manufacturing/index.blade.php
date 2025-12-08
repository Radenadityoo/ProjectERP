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
                                            <a href="{{ route('admin.manufacturing.show', $item) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" title="View">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.manufacturing.destroy', $item) }}" method="post" class="inline" onsubmit="return confirm('Delete this MO?');">
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

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
