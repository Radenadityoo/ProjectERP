<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Customers',
            'showViewSwitch' => false,
        ];

        $customers = [
            [
                'id' => 1,
                'name' => 'PT Maju Jaya',
                'email' => 'contact@majujaya.co.id',
                'phone' => '+62 21 5551234',
                'tags' => ['Corporate', 'VIP'],
                'total_spend' => 'Rp 85,000,000',
            ],
            [
                'id' => 2,
                'name' => 'CV Sentosa Makmur',
                'email' => 'info@sentosa.com',
                'phone' => '+62 21 5555678',
                'tags' => ['Corporate'],
                'total_spend' => 'Rp 72,000,000',
            ],
            [
                'id' => 3,
                'name' => 'UD Berkah Sejahtera',
                'email' => 'berkah@gmail.com',
                'phone' => '+62 813 8888 9999',
                'tags' => ['Retail', 'Local'],
                'total_spend' => 'Rp 65,000,000',
            ],
            [
                'id' => 4,
                'name' => 'Toko Elektronik Jaya',
                'email' => 'elektronikjaya@yahoo.com',
                'phone' => '+62 812 7777 6666',
                'tags' => ['Retail'],
                'total_spend' => 'Rp 48,000,000',
            ],
            [
                'id' => 5,
                'name' => 'PT Global Trading',
                'email' => 'sales@globaltrading.co.id',
                'phone' => '+62 21 5559999',
                'tags' => ['Corporate', 'International'],
                'total_spend' => 'Rp 38,000,000',
            ],
        ];

        return view('sales.customers.index', compact('commandbar', 'customers'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Create Customer',
            'showViewSwitch' => false,
        ];

        $available_tags = ['Corporate', 'Retail', 'VIP', 'Local', 'International', 'Distributor'];

        return view('sales.customers.create', compact('commandbar', 'available_tags'));
    }

    public function edit($id)
    {
        $commandbar = [
            'title' => 'Edit Customer',
            'showViewSwitch' => false,
        ];

        $customer = [
            'id' => $id,
            'name' => 'PT Maju Jaya',
            'email' => 'contact@majujaya.co.id',
            'phone' => '+62 21 5551234',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'tags' => ['Corporate', 'VIP'],
            'notes' => 'Premium customer with excellent payment history.',
        ];

        $available_tags = ['Corporate', 'Retail', 'VIP', 'Local', 'International', 'Distributor'];

        return view('sales.customers.edit', compact('commandbar', 'customer', 'available_tags'));
    }
}
