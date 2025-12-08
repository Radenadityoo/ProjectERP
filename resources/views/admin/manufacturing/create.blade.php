@extends('layouts.admin')

@php
    /** @var \Illuminate\Support\Collection $products */
    /** @var \Illuminate\Support\Collection $boms */
@endphp

@section('title', 'New Manufacturing Order')

@section('header')
    {{ $reference ?? 'New' }}
@endsection

@section('content')
    <div class="space-y-6">
        {{-- Header (sticky) --}}
        <div class="sticky top-0 z-20 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="px-6 py-4 flex items-center justify-between h-[72px]">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.manufacturing.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-white">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M15 18l-6-6 6-6"/></svg>
                    </a>

                        <div>
                            <div class="text-[20px] font-semibold text-gray-900 dark:text-gray-100">Manufacturing Order</div>
                            <div class="text-[14px] font-medium text-gray-700 dark:text-gray-300 mt-1">{{ $reference ?? 'WH/MO/0001' }} <button class="ml-2 text-erp">★</button></div>
                        </div>
                </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.manufacturing.index') }}" class="px-4 py-2 rounded-full border text-gray-700 dark:text-gray-200 h-[40px]">Cancel</a>
                        <form action="{{ route('admin.manufacturing.store') }}" method="POST" class="inline" id="moForm">
                            @csrf
                            <input type="hidden" name="reference" value="{{ $reference }}">
                            <button type="submit" class="px-4 py-2 rounded-full border bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 h-[40px]">Save</button>
                        </form>
                        <button class="px-4 py-2 rounded-full bg-erp text-white h-[40px]">Confirm</button>
                    </div>
            </div>
        </div>

        {{-- Overview Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <form id="moForm" action="{{ route('admin.manufacturing.store') }}" method="POST">
                @csrf
                <input type="hidden" name="reference" value="{{ $reference }}">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 dark:text-gray-300">Product</label>
                            <select name="product_id" class="mt-2 w-full border rounded px-3 h-[44px] border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">-- Select product --</option>
                                @foreach(($products ?? []) as $p)
                                    <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 dark:text-gray-300">Quantity</label>
                            <input type="number" name="quantity" step="0.01" class="mt-2 w-1/2 border rounded px-3 h-[44px] border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ old('quantity', 1) }}" id="moQuantity">
                        </div>

                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 dark:text-gray-300">Bill of Material</label>
                            <select name="bom_id" class="mt-2 w-full border rounded px-3 h-[44px] border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" id="bomSelect">
                                <option value="">-- Select BOM --</option>
                                @foreach(($boms ?? []) as $b)
                                    <option value="{{ $b->id }}" @selected(old('bom_id') == $b->id)>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 dark:text-gray-300">Deadline</label>
                            <input name="deadline" type="date" class="mt-2 w-full border rounded px-3 h-[44px] border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" value="{{ old('deadline') }}">
                        </div>
                    </div>

                    {{-- Status tracker full width below --}}
                    <div class="lg:col-span-2 mt-2">
                        <div class="flex items-center gap-6 mt-4">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full bg-erp"></div>
                                <div class="text-[12px] font-medium text-gray-700 dark:text-gray-300">Draft</div>
                            </div>

                            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-gray-700"></div>

                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full border border-gray-300 dark:border-gray-500"></div>
                                <div class="text-[12px] font-medium text-gray-700 dark:text-gray-300">Confirmed</div>
                            </div>

                            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-gray-700"></div>

                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full border border-gray-300 dark:border-gray-500"></div>
                                <div class="text-[12px] font-medium text-gray-700 dark:text-gray-300">Done</div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabs --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 p-4">
            <div class="flex items-center gap-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                <button class="py-3 px-6 text-sm font-medium border-b-2 border-erp">Components</button>
            </div>

            <div class="mt-4">
                {{-- Components table card --}}
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-300">Product</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Components will auto-populate when you select a Bill of Material</div>
                        </div>
                        <div class="text-sm font-medium text-gray-600 dark:text-gray-300">To Consume</div>
                    </div>

                    <div id="mo-components" class="space-y-2">
                        {{-- initial empty component row with placeholder select --}}
                        <div class="component-row flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-2/3">
                                <select name="components[0][component_product_id]" class="w-full border rounded px-3 h-[44px] border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="">-- Select product --</option>
                                    @foreach(($products ?? []) as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-1/3 text-right">
                                <input name="components[0][qty]" placeholder="Qty" class="text-right w-24 border rounded px-2 h-[40px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        <button type="button" id="mo-add-component" class="inline-flex items-center px-3 py-2 border rounded-md text-gray-700 dark:text-gray-200 h-[36px]">+ Add Component</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-erp { background-color: #5A8E74; }
    </style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const products = @json(($products ?? collect())->toArray());
    const container = document.getElementById('mo-components');
    const addBtn = document.getElementById('mo-add-component');
    const bomSelect = document.getElementById('bomSelect');
    const moQuantity = document.getElementById('moQuantity');

    // Handle BOM selection
    if(bomSelect) {
        bomSelect.addEventListener('change', function() {
            const bomId = this.value;
            if (!bomId) {
                clearComponents();
                return;
            }

            // Fetch BOM details
            fetch(`{{ route('admin.manufacturing.bom.details', ['bomId' => 'BOM_ID']) }}`.replace('BOM_ID', bomId))
                .then(response => response.json())
                .then(data => {
                    populateComponentsFromBom(data);
                })
                .catch(error => {
                    console.error('Error fetching BOM details:', error);
                });
        });
    }

    function clearComponents() {
        container.innerHTML = '';
        container.innerHTML = `
            <div class="component-row flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                <div class="w-2/3">
                    <select name="components[0][component_product_id]" class="w-full border rounded px-3 h-[44px] border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">-- Select product --</option>
                        ${products.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                    </select>
                </div>
                <div class="w-1/3 text-right">
                    <input name="components[0][qty]" placeholder="Qty" class="text-right w-24 border rounded px-2 h-[40px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" />
                </div>
            </div>
        `;
    }

    function populateComponentsFromBom(bom) {
        container.innerHTML = '';
        
        // Get the MO quantity (how much we're manufacturing)
        const moQty = parseFloat(moQuantity.value) || 1;
        
        // Get the BOM base quantity (what the BOM is designed for)
        const bomQty = parseFloat(bom.quantity) || 1;

        bom.components.forEach((component, idx) => {
            // Calculate needed qty: (component qty / bom base qty) * mo qty
            const calculatedQty = (component.qty / bomQty) * moQty;

            const div = document.createElement('div');
            div.className = 'component-row flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700';

            const left = document.createElement('div');
            left.className = 'w-2/3';
            
            // Display product name as readonly text or simple label
            const label = document.createElement('div');
            label.className = 'text-sm text-gray-900 dark:text-gray-100 font-medium';
            label.textContent = component.product_name;
            
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `components[${idx}][component_product_id]`;
            hiddenInput.value = component.component_product_id;
            
            left.appendChild(label);
            left.appendChild(hiddenInput);

            const right = document.createElement('div');
            right.className = 'w-1/3 text-right flex items-center justify-end gap-2';
            
            const qty = document.createElement('input');
            qty.name = `components[${idx}][qty]`;
            qty.type = 'number';
            qty.step = '0.01';
            qty.value = calculatedQty.toFixed(4);
            qty.className = 'text-right w-24 border rounded px-2 h-[40px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100';
            qty.readOnly = true; // Make it readonly since it's calculated
            
            right.appendChild(qty);

            const rem = document.createElement('button');
            rem.type = 'button';
            rem.className = 'text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm';
            rem.textContent = 'Remove';
            rem.addEventListener('click', function(e){
                e.preventDefault();
                div.remove();
                updateNames();
            });
            right.appendChild(rem);

            div.appendChild(left);
            div.appendChild(right);
            container.appendChild(div);
        });

        updateNames();
    }

    // Update quantities when MO quantity changes
    if(moQuantity) {
        moQuantity.addEventListener('change', function() {
            // If a BOM is selected, recalculate all component quantities
            const bomId = bomSelect.value;
            if (bomId) {
                fetch(`{{ route('admin.manufacturing.bom.details', ['bomId' => 'BOM_ID']) }}`.replace('BOM_ID', bomId))
                    .then(response => response.json())
                    .then(data => {
                        populateComponentsFromBom(data);
                    });
            }
        });
    }

    function addRow(){
        const idx = container.querySelectorAll('.component-row').length;
        const div = document.createElement('div');
        div.className = 'component-row flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700';

        const left = document.createElement('div');
        left.className = 'w-2/3';
        const select = document.createElement('select');
        select.name = `components[${idx}][component_product_id]`;
        select.className = 'w-full border rounded px-3 h-[44px] border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100';
        const opt0 = document.createElement('option');
        opt0.value = '';
        opt0.textContent = '-- Select product --';
        select.appendChild(opt0);
        products.forEach(p=>{
            const o = document.createElement('option');
            o.value = p.id;
            o.textContent = p.name;
            select.appendChild(o);
        });
        left.appendChild(select);

        const right = document.createElement('div');
        right.className = 'w-1/3 text-right flex items-center justify-end gap-2';
        const qty = document.createElement('input');
        qty.name = `components[${idx}][qty]`;
        qty.placeholder = 'Qty';
        qty.type = 'number';
        qty.step = '0.01';
        qty.className = 'text-right w-24 border rounded px-2 h-[40px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100';
        right.appendChild(qty);

        const rem = document.createElement('button');
        rem.type = 'button';
        rem.className = 'text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm';
        rem.textContent = 'Remove';
        rem.addEventListener('click', function(e){
            e.preventDefault();
            div.remove();
            updateNames();
        });
        right.appendChild(rem);

        div.appendChild(left);
        div.appendChild(right);
        container.appendChild(div);

        updateNames();
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

    if(addBtn) {
        addBtn.addEventListener('click', function(e){
            e.preventDefault();
            addRow();
        });
    }
});
</script>
@endpush

@endsection
