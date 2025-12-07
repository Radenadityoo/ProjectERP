<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="text-left text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-3 py-2">When</th>
                <th class="px-3 py-2">Ref</th>
                <th class="px-3 py-2">Product</th>
                <th class="px-3 py-2">Qty</th>
                <th class="px-3 py-2">Type</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @foreach($items as $it)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                    <td class="px-3 py-2">{{ $it->created_at->diffForHumans() }}</td>
                    <td class="px-3 py-2">{{ $it->reference }}</td>
                    <td class="px-3 py-2">{{ optional($it->product)->name }}</td>
                    <td class="px-3 py-2">{{ $it->quantity }}</td>
                    <td class="px-3 py-2">{{ $it->type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
