<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BomHeader;
use App\Models\BomComponent;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    public function index(Request $request)
    {
        $query = BomHeader::with('product');
        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $commandbar = [
            'title' => 'Bill of Materials',
            'count' => $items->total(),
            'showViewSwitch' => false,
        ];

        return view('admin.bom.index', compact('items','commandbar'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('admin.bom.form', ['products' => $products, 'bom' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'nullable|integer|exists:products,id',
            'quantity' => 'nullable|numeric',
            'components' => 'nullable|array',
            'components.*.component_product_id' => 'nullable|integer|exists:products,id',
            'components.*.qty' => 'nullable|numeric',
            'components.*.unit_cost' => 'nullable|numeric',
        ]);

        DB::transaction(function() use ($data, $request) {
            $header = BomHeader::create([
                'name' => $data['name'],
                'product_id' => $data['product_id'] ?? null,
                'quantity' => $data['quantity'] ?? 1,
                'total_cost' => 0,
            ]);

            $total = 0;
            $components = $request->input('components', []);
            foreach ($components as $row) {
                $qty = isset($row['qty']) ? floatval($row['qty']) : 0;
                $unit = isset($row['unit_cost']) ? floatval($row['unit_cost']) : 0;
                $subtotal = $qty * $unit;
                BomComponent::create([
                    'bom_id' => $header->id,
                    'component_product_id' => $row['component_product_id'] ?? null,
                    'qty' => $qty,
                    'unit_cost' => $unit,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $header->update(['total_cost' => $total]);
        });

        return redirect()->route('admin.bom.index')->with('success', 'BoM created.');
    }

    public function edit(BomHeader $bom)
    {
        $bom->load('components.product');
        $products = Product::orderBy('name')->get();
        return view('admin.bom.form', ['products' => $products, 'bom' => $bom]);
    }

    public function update(Request $request, BomHeader $bom)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'nullable|integer|exists:products,id',
            'quantity' => 'nullable|numeric',
            'components' => 'nullable|array',
            'components.*.component_product_id' => 'nullable|integer|exists:products,id',
            'components.*.qty' => 'nullable|numeric',
            'components.*.unit_cost' => 'nullable|numeric',
        ]);

        DB::transaction(function() use ($data, $request, $bom) {
            $bom->update([
                'name' => $data['name'],
                'product_id' => $data['product_id'] ?? null,
                'quantity' => $data['quantity'] ?? 1,
            ]);

            // simple approach: delete old components and reinsert
            $bom->components()->delete();

            $total = 0;
            $components = $request->input('components', []);
            foreach ($components as $row) {
                $qty = isset($row['qty']) ? floatval($row['qty']) : 0;
                $unit = isset($row['unit_cost']) ? floatval($row['unit_cost']) : 0;
                $subtotal = $qty * $unit;
                BomComponent::create([
                    'bom_id' => $bom->id,
                    'component_product_id' => $row['component_product_id'] ?? null,
                    'qty' => $qty,
                    'unit_cost' => $unit,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $bom->update(['total_cost' => $total]);
        });

        return redirect()->route('admin.bom.index')->with('success', 'BoM updated.');
    }

    public function destroy(BomHeader $bom)
    {
        $bom->delete();
        return redirect()->route('admin.bom.index')->with('success', 'BoM deleted.');
    }

    public function show(BomHeader $bom)
    {
        $bom->load('product', 'components');
        return view('admin.bom.show', compact('bom'));
    }
}
