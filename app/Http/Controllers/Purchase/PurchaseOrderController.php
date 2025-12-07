<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\PurchaseOrder::with('vendor');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('vendor', function ($v) use ($search) {
                        $v->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->orderByDesc('order_date')->get();
        $commandbar = [
            'title' => 'Purchase Orders',
            'count' => $orders->count(),
            'showViewSwitch' => false,
            'searchParam' => 'q',
        ];
        return view('purchase.orders.index', compact('orders', 'commandbar'));
    }

    public function create()
    {
        $products = \App\Models\Product::all();
        $vendors = \App\Models\Vendor::all();
        $commandbar = [
            'title' => 'Create Purchase Order',
            'count' => 0,
            'showViewSwitch' => false,
        ];
        return view('purchase.orders.create', compact('products', 'vendors', 'commandbar'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vendor_id' => 'required',
            'order_date' => 'required|date',
            'product_id' => 'nullable|array',
            'product_id.*' => 'nullable|exists:products,id',
            'description' => 'nullable|array',
            'quantity' => 'nullable|array',
            'unit_price' => 'nullable|array',
            'tax_rate' => 'nullable|array',
        ]);

        $po = \App\Models\PurchaseOrder::create([
            'vendor_id' => $data['vendor_id'],
            'order_date' => $data['order_date'],
            'status' => 'Draft',
            'total' => 0,
        ]);

        // Save items
        $total = 0;
        $productIds = $data['product_id'] ?? [];
        $descriptions = $data['description'] ?? [];
        $quantities = $data['quantity'] ?? [];
        $unitPrices = $data['unit_price'] ?? [];
        $taxRates = $data['tax_rate'] ?? [];

        for ($i = 0; $i < count($productIds); $i++) {
            if (empty($productIds[$i]) && empty($quantities[$i])) continue;
            
            $qty = floatval($quantities[$i] ?? 0);
            $price = floatval($unitPrices[$i] ?? 0);
            $tax = 0;
            if (isset($taxRates[$i])) {
                $taxStr = str_replace('%', '', $taxRates[$i]);
                $tax = floatval($taxStr);
            }
            $subtotal = $qty * $price;
            $taxAmount = $subtotal * ($tax / 100);
            
            \App\Models\PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $productIds[$i] ?: null,
                'description' => $descriptions[$i] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'tax_rate' => $tax,
                'subtotal' => $subtotal + $taxAmount,
            ]);
            
            $total += $subtotal + $taxAmount;
        }

        $po->update(['total' => $total]);

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order created successfully');
    }

    public function edit($id)
    {
        $po = \App\Models\PurchaseOrder::with(['items.product', 'vendor'])->findOrFail($id);
        $products = \App\Models\Product::all();
        $vendors = \App\Models\Vendor::all();
        $commandbar = [
            'title' => 'Edit Purchase Order',
            'count' => 0,
            'showViewSwitch' => false,
        ];
        return view('purchase.orders.edit', compact('po', 'products', 'vendors', 'commandbar'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'vendor_id' => 'required',
            'order_date' => 'required|date',
            'status' => 'in:Draft,Waiting,Purchase,Received',
            'product_id' => 'nullable|array',
            'product_id.*' => 'nullable|exists:products,id',
            'description' => 'nullable|array',
            'quantity' => 'nullable|array',
            'unit_price' => 'nullable|array',
            'tax_rate' => 'nullable|array',
        ]);

        $po = \App\Models\PurchaseOrder::findOrFail($id);
        $po->update([
            'vendor_id' => $data['vendor_id'],
            'order_date' => $data['order_date'],
            'status' => $data['status'],
        ]);

        // Delete existing items and recreate
        $po->items()->delete();

        // Save items
        $total = 0;
        $productIds = $data['product_id'] ?? [];
        $descriptions = $data['description'] ?? [];
        $quantities = $data['quantity'] ?? [];
        $unitPrices = $data['unit_price'] ?? [];
        $taxRates = $data['tax_rate'] ?? [];

        for ($i = 0; $i < count($productIds); $i++) {
            if (empty($productIds[$i]) && empty($quantities[$i])) continue;
            
            $qty = floatval($quantities[$i] ?? 0);
            $price = floatval($unitPrices[$i] ?? 0);
            $tax = 0;
            if (isset($taxRates[$i])) {
                $taxStr = str_replace('%', '', $taxRates[$i]);
                $tax = floatval($taxStr);
            }
            $subtotal = $qty * $price;
            $taxAmount = $subtotal * ($tax / 100);
            
            \App\Models\PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $productIds[$i] ?: null,
                'description' => $descriptions[$i] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'tax_rate' => $tax,
                'subtotal' => $subtotal + $taxAmount,
            ]);
            
            $total += $subtotal + $taxAmount;
        }

        $po->update(['total' => $total]);

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order updated successfully');
    }
}
