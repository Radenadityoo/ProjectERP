@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="mb-2">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Internal Transfers</h1>
        </div>
        <div class="mb-4">
            <a href="{{ route('admin.inventory.transfers.create') }}" class="inline-flex items-center px-4 py-2 bg-erp text-white rounded hover:bg-opacity-90">New Transfer</a>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-xs text-gray-500 dark:text-gray-400 uppercase">
                        <tr>
                            <th class="px-3 py-2">Reference</th>
                            <th class="px-3 py-2">From</th>
                            <th class="px-3 py-2">To</th>
                            <th class="px-3 py-2">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($items as $it)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 text-gray-900 dark:text-gray-100">
                                <td class="px-3 py-2">{{ $it->reference }}</td>
                                <td class="px-3 py-2">{{ $it->from_location }}</td>
                                <td class="px-3 py-2">{{ $it->to_location }}</td>
                                <td class="px-3 py-2">{{ optional($it->transferred_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
@endsection
