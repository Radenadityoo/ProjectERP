@extends('layouts.admin')

@php
    /** @var \App\Models\BomHeader|null $bom */
    /** @var \Illuminate\Support\Collection $products */
@endphp

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">{{ $bom ? 'Edit BoM' : 'Create BoM' }}</h1>
            <div>
                <a href="{{ route('admin.bom.index') }}" class="inline-flex items-center px-3 py-2 border rounded">Back</a>
            </div>
        </div>

        <form method="post" action="{{ $bom ? route('admin.bom.update', $bom) : route('admin.bom.store') }}">
            @csrf
            @if($bom)
                @method('put')
            @endif

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="col-span-2">
                    <label class="block text-sm">Name</label>
                    <input name="name" value="{{ old('name', $bom->name ?? '') }}" class="w-full border rounded p-2 bg-white dark:bg-gray-900" />
                </div>
                <div>
                    <label class="block text-sm">Product</label>
                    <select name="product_id" class="w-full border rounded p-2 bg-white dark:bg-gray-900">
                        <option value="">-- Select product --</option>
                        @foreach(($products ?? []) as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $bom->product_id ?? '') == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4 bg-white dark:bg-gray-800 p-4 rounded shadow">
                <h2 class="font-medium mb-2">Components</h2>
                <div id="components">
                    @php
                        $rows = old('components', $bom ? $bom->components->toArray() : []);
                    @endphp
                    @if(count($rows) === 0)
                        <div class="component-row grid grid-cols-5 gap-2 items-center mb-2">
                            <select name="components[0][component_product_id]" class="border rounded p-2 bg-white dark:bg-gray-900">
                                <option value="">-- Select product --</option>
                                @foreach(($products ?? []) as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            <input name="components[0][qty]" placeholder="Qty" class="border rounded p-2 bg-white dark:bg-gray-900" />
                            <input name="components[0][unit_cost]" placeholder="Unit Cost" class="border rounded p-2 bg-white dark:bg-gray-900" />
                            <div class="text-sm text-gray-500">Subtotal</div>
                            <button type="button" class="remove-row text-red-600">Remove</button>
                        </div>
                    @else
                        @foreach($rows as $i => $row)
                            <div class="component-row grid grid-cols-5 gap-2 items-center mb-2">
                                <select name="components[{{ $i }}][component_product_id]" class="border rounded p-2 bg-white dark:bg-gray-900">
                                    <option value="">--</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" @selected(($row['component_product_id'] ?? null) == $p->id)>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                <input name="components[{{ $i }}][qty]" value="{{ $row['qty'] ?? '' }}" placeholder="Qty" class="border rounded p-2 bg-white dark:bg-gray-900 qty" />
                                <input name="components[{{ $i }}][unit_cost]" value="{{ $row['unit_cost'] ?? '' }}" placeholder="Unit Cost" class="border rounded p-2 bg-white dark:bg-gray-900 unit_cost" />
                                <div class="subtotal text-sm text-gray-500">{{ isset($row['subtotal']) ? currency($row['subtotal'], $defaultCurrency ?? 'IDR') : '0.00' }}</div>
                                <button type="button" class="remove-row text-red-600">Remove</button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="mt-3">
                    <button type="button" id="add-row" class="px-3 py-1 border rounded">Add Row</button>
                </div>

                <div class="mt-4 text-right">
                    <div class="font-medium">Total: <span id="total">0.00</span></div>
                </div>
            </div>

            <div>
                <button class="px-4 py-2 bg-erp text-white rounded">Save</button>
            </div>
        </form>
    </div>

@push('scripts')
<script>
    (function(){
        document.addEventListener('DOMContentLoaded', function(){
            const products = @json(($products ?? collect())->toArray());
            const container = document.getElementById('components');
            const addBtn = document.getElementById('add-row');

        function recalc(){
            let total = 0;
            document.querySelectorAll('.component-row').forEach(row=>{
                const qty = parseFloat(row.querySelector('[name$="[qty]"]').value || 0);
                const unit = parseFloat(row.querySelector('[name$="[unit_cost]"]').value || 0);
                const subtotal = qty * unit;
                const subEl = row.querySelector('.subtotal');
                if(subEl) subEl.textContent = subtotal.toFixed(2);
                total += subtotal;
            });
            document.getElementById('total').textContent = total.toFixed(2);
        }

        function addRow(data){
            const idx = container.querySelectorAll('.component-row').length;
            const div = document.createElement('div');
            div.className = 'component-row grid grid-cols-5 gap-2 items-center mb-2';

            // select
            const select = document.createElement('select');
            select.name = `components[${idx}][component_product_id]`;
            select.className = 'border rounded p-2 bg-white dark:bg-gray-900';
            const opt0 = document.createElement('option'); opt0.value = ''; opt0.textContent = '-- Select product --';
            select.appendChild(opt0);
            products.forEach(p=>{
                const o = document.createElement('option'); o.value = p.id; o.textContent = p.name;
                if(data && data.component_product_id == p.id) o.selected = true;
                select.appendChild(o);
            });

            // qty
            const qty = document.createElement('input');
            qty.name = `components[${idx}][qty]`;
            qty.placeholder = 'Qty';
            qty.className = 'border rounded p-2 bg-white dark:bg-gray-900 qty';
            qty.value = data ? (data.qty || '') : '';

            // unit cost
            const unit = document.createElement('input');
            unit.name = `components[${idx}][unit_cost]`;
            unit.placeholder = 'Unit Cost';
            unit.className = 'border rounded p-2 bg-white dark:bg-gray-900 unit_cost';
            unit.value = data ? (data.unit_cost || '') : '';

            // subtotal
            const subDiv = document.createElement('div');
            subDiv.className = 'subtotal text-sm text-gray-500';
            subDiv.textContent = '0.00';

            // remove
            const rem = document.createElement('button');
            rem.type = 'button'; rem.className = 'remove-row text-red-600'; rem.textContent = 'Remove';
            rem.addEventListener('click', ()=>{ div.remove(); recalc(); updateNames(); });

            div.appendChild(select);
            div.appendChild(qty);
            div.appendChild(unit);
            div.appendChild(subDiv);
            div.appendChild(rem);

            container.appendChild(div);

            [select, qty, unit].forEach(el=>el.addEventListener('input', recalc));
            recalc();
        }

        function updateNames(){
            container.querySelectorAll('.component-row').forEach((row, i)=>{
                row.querySelectorAll('select, input').forEach(el=>{
                    const name = el.getAttribute('name');
                    if(!name) return;
                    const newName = name.replace(/components\[\d+\]/, `components[${i}]`);
                    el.setAttribute('name', newName);
                });
            });
        }

        // attach existing listeners
        container.querySelectorAll('.component-row').forEach(row=>{
            row.querySelectorAll('input, select').forEach(el=>el.addEventListener('input', recalc));
            const rem = row.querySelector('.remove-row');
            if(rem) rem.addEventListener('click', ()=>{ row.remove(); recalc(); updateNames(); });
        });

            if(addBtn) addBtn.addEventListener('click', ()=> addRow());
            // initial calc
            recalc();
        });
    })();
</script>
@endpush

@endsection
