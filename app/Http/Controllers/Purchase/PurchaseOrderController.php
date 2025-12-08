<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\PurchaseOrder::with('vendor:id,name')
            ->select('id', 'po_number', 'vendor_id', 'order_date', 'expected_arrival', 'status', 'currency', 'total', 'total_base');

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('vendor', function ($v) use ($search) {
                        $v->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->orderByDesc('order_date')->paginate(25);
        $commandbar = [
            'title' => 'Purchase Orders',
            'count' => $orders->total(),
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
            'currency_code' => 'nullable|string|size:3',
            'product_id' => 'nullable|array',
            'product_id.*' => 'nullable|exists:products,id',
            'description' => 'nullable|array',
            'quantity' => 'nullable|array',
            'unit_price' => 'nullable|array',
            'tax_rate' => 'nullable|array',
        ]);

        $currencyCode = strtoupper($data['currency_code'] ?? $request->input('currency', setting('currency.default', base_currency())));
        $rateToBase = currency_rate_to_base($currencyCode);

        $po = \App\Models\PurchaseOrder::create([
            'vendor_id' => $data['vendor_id'],
            'order_date' => $data['order_date'],
            'currency' => $currencyCode,
            'exchange_rate' => $rateToBase,
            'status' => 'Draft',
            'subtotal' => 0,
            'tax_amount' => 0,
            'total' => 0,
            'subtotal_base' => 0,
            'tax_amount_base' => 0,
            'total_base' => 0,
        ]);

        // Save items
        $total = 0;
        $totalBase = 0;
        $taxTotal = 0;
        $taxTotalBase = 0;
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
            $subtotalBase = convert_to_base($subtotal, $currencyCode);
            $taxAmountBase = convert_to_base($taxAmount, $currencyCode);
            
            \App\Models\PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $productIds[$i] ?: null,
                'description' => $descriptions[$i] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'tax_rate' => $tax,
                'subtotal' => $subtotal + $taxAmount,
                'currency_code' => $currencyCode,
                'exchange_rate' => $rateToBase,
                'unit_price_base' => convert_to_base($price, $currencyCode),
                'subtotal_base' => $subtotalBase + $taxAmountBase,
            ]);
            
            $total += $subtotal + $taxAmount;
            $taxTotal += $taxAmount;
            $totalBase += $subtotalBase + $taxAmountBase;
            $taxTotalBase += $taxAmountBase;
        }

        $po->update([
            'subtotal' => $total - $taxTotal,
            'tax_amount' => $taxTotal,
            'total' => $total,
            'subtotal_base' => $totalBase - $taxTotalBase,
            'tax_amount_base' => $taxTotalBase,
            'total_base' => $totalBase,
        ]);

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
            'currency_code' => 'nullable|string|size:3',
            'product_id' => 'nullable|array',
            'product_id.*' => 'nullable|exists:products,id',
            'description' => 'nullable|array',
            'quantity' => 'nullable|array',
            'unit_price' => 'nullable|array',
            'tax_rate' => 'nullable|array',
        ]);

        $po = \App\Models\PurchaseOrder::findOrFail($id);
        $oldStatus = $po->status;
        $newStatus = $data['status'];
        
        $currencyCode = strtoupper($data['currency_code'] ?? $po->currency ?? setting('currency.default', base_currency()));
        $rateToBase = currency_rate_to_base($currencyCode);
        $po->update([
            'vendor_id' => $data['vendor_id'],
            'order_date' => $data['order_date'],
            'status' => $data['status'],
            'currency' => $currencyCode,
            'exchange_rate' => $rateToBase,
        ]);

        // Delete existing items and recreate
        $po->items()->delete();

        // Save items
        $total = 0;
        $totalBase = 0;
        $taxTotal = 0;
        $taxTotalBase = 0;
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
            $subtotalBase = convert_to_base($subtotal, $currencyCode);
            $taxAmountBase = convert_to_base($taxAmount, $currencyCode);
            
            \App\Models\PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id' => $productIds[$i] ?: null,
                'description' => $descriptions[$i] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'tax_rate' => $tax,
                'subtotal' => $subtotal + $taxAmount,
                'currency_code' => $currencyCode,
                'exchange_rate' => $rateToBase,
                'unit_price_base' => convert_to_base($price, $currencyCode),
                'subtotal_base' => $subtotalBase + $taxAmountBase,
            ]);
            
            $total += $subtotal + $taxAmount;
            $taxTotal += $taxAmount;
            $totalBase += $subtotalBase + $taxAmountBase;
            $taxTotalBase += $taxAmountBase;
        }

        $po->update([
            'subtotal' => $total - $taxTotal,
            'tax_amount' => $taxTotal,
            'total' => $total,
            'subtotal_base' => $totalBase - $taxTotalBase,
            'tax_amount_base' => $taxTotalBase,
            'total_base' => $totalBase,
        ]);

        // Handle inventory when status changes to Received
        if ($newStatus === 'Received' && $oldStatus !== 'Received') {
            $this->increaseInventoryForPurchaseOrder($po);
        }
        // Reverse inventory if status changes from Received back
        elseif ($oldStatus === 'Received' && $newStatus !== 'Received') {
            $this->reverseInventoryForPurchaseOrder($po);
        }

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order updated successfully');
    }

    /**
     * Increase inventory when purchase order is received
     */
    protected function increaseInventoryForPurchaseOrder(\App\Models\PurchaseOrder $po)
    {
        foreach ($po->items as $item) {
            if (!$item->product_id) continue;
            
            $product = Product::find($item->product_id);
            if ($product && $product->track_inventory) {
                // Increase product quantity
                $product->increment('quantity', $item->quantity);

                // Create stock movement record
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'purchase_order',
                    'reference' => $po->po_number,
                    'quantity' => $item->quantity,
                    'source' => $po->vendor->name ?? 'Vendor',
                    'notes' => "Received via Purchase Order {$po->po_number}",
                ]);
            }
        }
    }

    /**
     * Reverse inventory when purchase order status changes from received
     */
    protected function reverseInventoryForPurchaseOrder(\App\Models\PurchaseOrder $po)
    {
        foreach ($po->items as $item) {
            if (!$item->product_id) continue;
            
            $product = Product::find($item->product_id);
            if ($product && $product->track_inventory) {
                // Reduce product quantity
                $product->decrement('quantity', $item->quantity);
            }
        }

        // Delete related stock movements
        StockMovement::where('type', 'purchase_order')
            ->where('reference', $po->po_number)
            ->delete();
    }
}
