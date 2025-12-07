<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Vendor::query();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $vendors = $query->orderBy('name')->get();
        $commandbar = [
            'title' => 'Vendors',
            'count' => $vendors->count(),
            'showViewSwitch' => false,
            'searchParam' => 'q',
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
            'address' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'tags' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $data['total_spend'] = 0;
        $vendor = \App\Models\Vendor::create($data);

        return redirect()->route('purchase.vendors.index')->with('success', 'Vendor created successfully.');
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
        $vendor = \App\Models\Vendor::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'tags' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $vendor->update($data);

        return redirect()->route('purchase.vendors.index')->with('success', 'Vendor updated successfully.');
    }
}
