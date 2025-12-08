@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="mb-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Bill of Materials</h1>
        </div>
        <div class="mb-4">
            <a href="{{ route('admin.bom.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded shadow hover:bg-opacity-90">Create BoM</a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded">
            <div class="p-4">
                <form method="get" class="mb-4">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search BoM" class="w-full border rounded p-2 bg-gray-50 dark:bg-gray-900" />
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-xs text-gray-500 uppercase">
                            <tr>
                                <th class="px-3 py-2">Name</th>
                                <th class="px-3 py-2">Product</th>
                                <th class="px-3 py-2">Quantity</th>
                                <th class="px-3 py-2">Total Cost</th>
                                <th class="px-3 py-2">Created</th>
                                <th class="px-3 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                    <td class="px-3 py-2">{{ $item->name }}</td>
                                    <td class="px-3 py-2">{{ optional($item->product)->name }}</td>
                                    <td class="px-3 py-2">{{ $item->quantity }}</td>
                                    <td class="px-3 py-2">{{ number_format($item->total_cost, 2) }}</td>
                                    <td class="px-3 py-2">{{ $item->created_at->format('Y-m-d') }}</td>
                                    <td class="px-3 py-2">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.bom.show', $item) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" title="View">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.bom.edit', $item) }}" class="text-[#5A8E74] hover:text-[#4a7a64]" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.bom.destroy', $item) }}" method="post" class="inline" onsubmit="return confirm('Delete this BoM?');">
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
