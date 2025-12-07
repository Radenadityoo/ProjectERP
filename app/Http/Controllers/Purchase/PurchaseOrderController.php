<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = \App\Models\PurchaseOrder::with('vendor')->orderByDesc('order_date')->get();
        $commandbar = [
            'title' => 'Purchase Orders',
            'count' => $orders->count(),
            'showViewSwitch' => false,
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
        ]);

        $data['status'] = 'Draft';
        $po = \App\Models\PurchaseOrder::create($data);

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order saved ID '.$po->id);
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
        ]);

        $po = \App\Models\PurchaseOrder::findOrFail($id);
        $po->update($data);

        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order updated ID '.$id);
    }
}
