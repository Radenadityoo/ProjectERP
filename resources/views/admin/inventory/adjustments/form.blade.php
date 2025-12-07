@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-4">New Adjustment</h1>

        <form method="post" action="{{ route('admin.inventory.adjustments.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm">Reference</label>
                    <input name="reference" class="w-full border rounded p-2 bg-white dark:bg-gray-900" value="{{ old('reference', 'ADJ-'.date('YmdHis')) }}" />
                </div>
                <div>
                    <label class="block text-sm">Reason</label>
                    <input name="reason" class="w-full border rounded p-2 bg-white dark:bg-gray-900" />
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                <h2 class="font-medium mb-2">Items</h2>
                <div id="items">
                    <div class="grid grid-cols-4 gap-2 mb-2 item-row">
                        <select name="items[0][product_id]" class="border rounded p-2 bg-white dark:bg-gray-900">
                            <option value="">-- Select Product --</option>
                            @foreach(App\Models\Product::orderBy('name')->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <input name="items[0][qty]" placeholder="Qty (negative/positive)" class="border rounded p-2 bg-white dark:bg-gray-900" />
                        <input name="items[0][unit_cost]" placeholder="Unit cost" class="border rounded p-2 bg-white dark:bg-gray-900" />
                        <button type="button" class="remove-item text-red-600">Remove</button>
                    </div>
                </div>
                <div>
                    <button type="button" id="add-item" class="px-3 py-1 border rounded">Add Item</button>
                </div>
            </div>

            <div>
                <button class="px-4 py-2 bg-erp text-white rounded">Save Adjustment</button>
            </div>
        </form>
    </div>

@push('scripts')
<script>
    (function(){
        const container = document.getElementById('items');
        document.getElementById('add-item').addEventListener('click', function(){
            const idx = container.querySelectorAll('.item-row').length;
            const div = document.createElement('div');
            div.className = 'grid grid-cols-4 gap-2 mb-2 item-row';
            div.innerHTML = `
                <select name="items[${idx}][product_id]" class="border rounded p-2 bg-white dark:bg-gray-900">
                    <option value="">-- Select Product --</option>
                    ${(() => { let s=''; /* placeholder */ return s; })()}
                </select>
                <input name="items[${idx}][qty]" placeholder="Qty (negative/positive)" class="border rounded p-2 bg-white dark:bg-gray-900" />
                <input name="items[${idx}][unit_cost]" placeholder="Unit cost" class="border rounded p-2 bg-white dark:bg-gray-900" />
                <button type="button" class="remove-item text-red-600">Remove</button>
            `;
            container.appendChild(div);
            div.querySelector('.remove-item').addEventListener('click', ()=>div.remove());
        });

        container.querySelectorAll('.remove-item').forEach(btn=>btn.addEventListener('click', function(){ this.closest('.item-row').remove(); }));
    })();
</script>
@endpush

@endsection
