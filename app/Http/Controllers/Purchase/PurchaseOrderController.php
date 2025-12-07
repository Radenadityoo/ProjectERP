<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $items = [
            ['number' => 'PO-2025-0012', 'vendor' => 'Acme Supplies', 'order_date' => '2025-12-03', 'arrival' => '2025-12-10', 'status' => 'Draft', 'total' => 18250],
            ['number' => 'PO-2025-0011', 'vendor' => 'Northwind Traders', 'order_date' => '2025-12-01', 'arrival' => '2025-12-08', 'status' => 'Waiting', 'total' => 6400],
            ['number' => 'PO-2025-0010', 'vendor' => 'Globex', 'order_date' => '2025-11-28', 'arrival' => '2025-12-06', 'status' => 'Purchase', 'total' => 12800],
            ['number' => 'PO-2025-0009', 'vendor' => 'Innotech', 'order_date' => '2025-11-25', 'arrival' => '2025-12-02', 'status' => 'Received', 'total' => 9200],
        ];

        $commandbar = [
            'title' => 'Purchase Orders',
            'count' => count($items),
            'showViewSwitch' => false,
        ];

        return view('purchase.orders.index', compact('items', 'commandbar'));
    }

    public function create()
    {
        $products = [
            ['id' => 1, 'name' => 'Aluminum Plate'],
            ['id' => 2, 'name' => 'Control Board PCB'],
            ['id' => 3, 'name' => 'Motor Assembly'],
            ['id' => 4, 'name' => 'Steel Frame'],
        ];

        $vendors = [
            ['id' => 1, 'name' => 'Acme Supplies'],
            ['id' => 2, 'name' => 'Northwind Traders'],
            ['id' => 3, 'name' => 'Globex'],
        ];

        $commandbar = [
            'title' => 'Create Purchase Order',
            'count' => 0,
            'showViewSwitch' => false,
        ];

        return view('purchase.orders.create', compact('products', 'vendors', 'commandbar'));
    }

    public function edit($id)
    {
        $po = [
            'id' => $id,
            'reference' => 'PO-2025-0012',
            'vendor_id' => 1,
            'order_date' => '2025-12-08',
            'currency' => 'USD',
            'status' => 'Draft',
        ];

        $lines = [
            ['product' => 'Aluminum Plate', 'desc' => 'Aluminum Plate 5mm', 'qty' => 10, 'price' => 45, 'tax' => 'VAT 10%', 'subtotal' => 450],
            ['product' => 'Control Board PCB', 'desc' => 'PCB assembly', 'qty' => 5, 'price' => 220, 'tax' => 'VAT 10%', 'subtotal' => 1100],
        ];

        $products = [
            ['id' => 1, 'name' => 'Aluminum Plate'],
            ['id' => 2, 'name' => 'Control Board PCB'],
            ['id' => 3, 'name' => 'Motor Assembly'],
        ];

        $vendors = [
            ['id' => 1, 'name' => 'Acme Supplies'],
            ['id' => 2, 'name' => 'Northwind Traders'],
        ];

        $commandbar = [
            'title' => 'Edit Purchase Order',
            'count' => 0,
            'showViewSwitch' => false,
        ];

        return view('purchase.orders.edit', compact('po', 'lines', 'products', 'vendors', 'commandbar'));
    }
}
