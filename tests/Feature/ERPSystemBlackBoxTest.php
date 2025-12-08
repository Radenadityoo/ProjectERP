<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\Product;

/**
 * Black Box Testing for ERP System-Wide Workflows
 * 
 * Tests integration between modules and general system behavior.
 */

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('authenticated user can access dashboard', function () {
    $response = $this->actingAs($this->user)->get(route('dashboard'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
});

test('unauthenticated user is redirected from dashboard', function () {
    $response = $this->get(route('dashboard'));
    
    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('user profile can be viewed and edited', function () {
    $response = $this->actingAs($this->user)->get(route('profile.edit'));
    
    $response->assertStatus(200);
    $response->assertViewHas('user');
});

test('user can update profile information', function () {
    $newName = 'Updated Name';
    
    $response = $this->actingAs($this->user)
        ->patch(route('profile.update'), [
            'name' => $newName,
            'email' => $this->user->email,
        ]);
    
    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => $newName,
    ]);
});

test('customer list is accessible', function () {
    Customer::factory(5)->create();
    
    $response = $this->actingAs($this->user)->get(route('sales.customers.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
    $response->assertViewHas('customers');
});

test('new customer can be created', function () {
    $customerData = [
        'name' => 'Test Customer Ltd',
        'email' => 'test@customer.com',
        'phone' => '08123456789',
        'address' => 'Main Street 123',
        'city' => 'Jakarta',
        'state' => 'DKI Jakarta',
        'postal_code' => '12345',
        'country' => 'Indonesia',
    ];
    
    $response = $this->actingAs($this->user)
        ->post(route('sales.customers.store'), $customerData);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('customers', [
        'name' => 'Test Customer Ltd',
        'email' => 'test@customer.com',
    ]);
});

test('vendor list is accessible', function () {
    Vendor::factory(5)->create();
    
    $response = $this->actingAs($this->user)->get(route('purchase.vendors.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
    $response->assertViewHas('vendors');
});

test('new vendor can be created', function () {
    $vendorData = [
        'name' => 'Test Vendor Inc',
        'email' => 'vendor@test.com',
        'phone' => '08198765432',
        'address' => 'Industrial Area',
        'city' => 'Surabaya',
        'state' => 'East Java',
        'postal_code' => '60123',
        'country' => 'Indonesia',
    ];
    
    $response = $this->actingAs($this->user)
        ->post(route('purchase.vendors.store'), $vendorData);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('vendors', [
        'name' => 'Test Vendor Inc',
        'email' => 'vendor@test.com',
    ]);
});

test('product list is accessible', function () {
    Product::factory(10)->create();
    
    $response = $this->actingAs($this->user)->get(route('admin.inventory.products.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
    $response->assertViewHas('products');
});

test('new product can be created', function () {
    $productData = [
        'name' => 'Test Product SKU123',
        'sku' => 'SKU-123-ABC',
        'description' => 'A test product',
        'price' => 50000,
        'quantity' => 100,
        'unit' => 'piece',
        'category' => 'Electronics',
    ];
    
    $response = $this->actingAs($this->user)
        ->post(route('admin.inventory.products.store'), $productData);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('products', [
        'name' => 'Test Product SKU123',
        'sku' => 'SKU-123-ABC',
    ]);
});

test('user can access sales dashboard', function () {
    $response = $this->actingAs($this->user)->get(route('sales.dashboard'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
});

test('user can access purchase dashboard', function () {
    $response = $this->actingAs($this->user)->get(route('purchase.dashboard'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
});

test('user can access invoicing dashboard', function () {
    $response = $this->actingAs($this->user)->get(route('invoicing.dashboard'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
});

test('navigation to main pages works', function () {
    $pages = [
        route('dashboard'),
        route('sales.orders.index'),
        route('purchase.orders.index'),
        route('invoicing.invoices.index'),
        route('invoicing.payments.index'),
    ];
    
    foreach ($pages as $page) {
        $response = $this->actingAs($this->user)->get($page);
        $response->assertStatus(200);
    }
});

test('commandbar displays correct record count', function () {
    $customers = Customer::factory(5)->create();
    
    $response = $this->actingAs($this->user)->get(route('sales.customers.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar', function ($commandbar) {
        return $commandbar['count'] > 0;
    });
});

test('search functionality is available on listing pages', function () {
    Customer::factory(3)->create(['name' => 'Searchable Name']);
    
    $response = $this->actingAs($this->user)
        ->get(route('sales.customers.index', ['q' => 'Searchable']));
    
    $response->assertStatus(200);
});

test('user can navigate between modules', function () {
    $modules = [
        'sales' => route('sales.dashboard'),
        'purchase' => route('purchase.dashboard'),
        'invoicing' => route('invoicing.dashboard'),
    ];
    
    foreach ($modules as $module => $route) {
        $response = $this->actingAs($this->user)->get($route);
        $response->assertStatus(200);
    }
});

test('invalid record returns 404', function () {
    $response = $this->actingAs($this->user)
        ->get(route('sales.orders.edit', ['order' => 99999]));
    
    $response->assertStatus(404);
});

test('delete operations return redirect on success', function () {
    $customer = Customer::factory()->create();
    
    $response = $this->actingAs($this->user)
        ->delete(route('sales.customers.destroy', $customer->id));
    
    $response->assertStatus(302);
});

test('form validation works on create operations', function () {
    $response = $this->actingAs($this->user)
        ->post(route('sales.customers.store'), [
            // Empty data to trigger validation
        ]);
    
    $response->assertStatus(302); // Redirect with errors
});
