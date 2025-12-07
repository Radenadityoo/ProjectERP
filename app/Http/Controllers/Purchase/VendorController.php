<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = \App\Models\Vendor::all();
        $commandbar = [
            'title' => 'Vendors',
            'count' => $vendors->count(),
            'showViewSwitch' => false,
        ];
        return view('purchase.vendors.index', compact('vendors', 'commandbar'));
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $id = now()->timestamp;

        return redirect()->route('purchase.vendors.index')->with('success', 'Vendor saved (demo) ID '.$id);
    }

    public function edit($id)
    {
        $vendor = \App\Models\Vendor::findOrFail($id);
        $commandbar = [
            'title' => 'Edit Vendor',
            'count' => 0,
            'showViewSwitch' => false,
        ];
        return view('purchase.vendors.edit', compact('vendor', 'commandbar'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        return redirect()->route('purchase.vendors.index')->with('success', 'Vendor updated (demo) ID '.$id);
    }
}
