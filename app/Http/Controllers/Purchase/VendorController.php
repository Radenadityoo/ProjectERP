<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $items = [
            ['id' => 1, 'name' => 'Acme Supplies', 'email' => 'sales@acme.com', 'phone' => '+1 555 1200', 'terms' => '30 days', 'tags' => ['Preferred','Hardware'], 'spend' => 52300],
            ['id' => 2, 'name' => 'Northwind Traders', 'email' => 'contact@northwind.com', 'phone' => '+1 555 9988', 'terms' => '15 days', 'tags' => ['Electronics'], 'spend' => 38600],
            ['id' => 3, 'name' => 'Globex', 'email' => 'info@globex.com', 'phone' => '+44 20 1234', 'terms' => '45 days', 'tags' => ['Overseas'], 'spend' => 28500],
        ];

        $commandbar = [
            'title' => 'Vendors',
            'count' => count($items),
            'showViewSwitch' => false,
        ];

        return view('purchase.vendors.index', compact('items', 'commandbar'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Create Vendor',
            'count' => 0,
            'showViewSwitch' => false,
        ];

        return view('purchase.vendors.create', compact('commandbar'));
    }

    public function edit($id)
    {
        $vendor = [
            'id' => $id,
            'name' => 'Acme Supplies',
            'email' => 'sales@acme.com',
            'phone' => '+1 555 1200',
            'address' => '123 Industrial Ave, Tech City, TC 12345',
            'terms' => '30',
            'notes' => 'Preferred vendor for hardware supplies',
            'tags' => ['Preferred', 'Hardware'],
        ];

        $commandbar = [
            'title' => 'Edit Vendor',
            'count' => 0,
            'showViewSwitch' => false,
        ];

        return view('purchase.vendors.edit', compact('vendor', 'commandbar'));
    }
}
