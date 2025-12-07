@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Stock Movements</h1>
            <form method="get" class="w-1/3">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search reference" class="w-full border rounded p-2 bg-gray-50 dark:bg-gray-900" />
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-3 py-2">When</th>
                            <th class="px-3 py-2">Reference</th>
                            <th class="px-3 py-2">Product</th>
                            <th class="px-3 py-2">Type</th>
                            <th class="px-3 py-2">Qty</th>
                            <th class="px-3 py-2">Source</th>
                            <th class="px-3 py-2">Destination</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($items as $it)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-3 py-2">{{ $it->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-3 py-2">{{ $it->reference }}</td>
                                <td class="px-3 py-2">{{ optional($it->product)->name }}</td>
                                <td class="px-3 py-2">{{ $it->type }}</td>
                                <td class="px-3 py-2">{{ $it->quantity }}</td>
                                <td class="px-3 py-2">{{ $it->source }}</td>
                                <td class="px-3 py-2">{{ $it->destination }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
@endsection
