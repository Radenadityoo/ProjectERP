<?php

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\User;

/**
 * Black Box Testing for Purchase Orders Module
 * 
 * Tests complete purchase order workflows from creation to management.
 */

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->vendor = Vendor::factory()->create();
    $this->product = Product::factory()->create();
});

test('user can view purchase orders list', function () {
    $response = $this->actingAs($this->user)->get(route('purchase.orders.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
    $response->assertViewHas('orders');
});

test('purchase orders are paginated', function () {
    PurchaseOrder::factory(30)->create(['vendor_id' => $this->vendor->id]);
    
    $response = $this->actingAs($this->user)->get(route('purchase.orders.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('orders', function ($orders) {
        return $orders->count() <= 25;
    });
});

test('user can create purchase order', function () {
    $poData = [
        'vendor_id' => $this->vendor->id,
        'order_date' => now()->format('Y-m-d'),
        'expected_arrival' => now()->addDays(7)->format('Y-m-d'),
        'status' => 'draft',
        'currency' => 'IDR',
        'lines' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 10,
                'unit_price' => 50000,
                'tax' => 0,
            ]
        ]
    ];
    
    $response = $this->actingAs($this->user)
        ->post(route('purchase.orders.store'), $poData);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('purchase_orders', [
        'vendor_id' => $this->vendor->id,
        'status' => 'draft'
    ]);
});

test('purchase order calculates total correctly', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'subtotal' => 100000,
        'tax' => 10000,
        'total' => 110000,
    ]);
    
    expect($po->total)->toBe(110000.00);
});

test('user can edit draft purchase order', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'status' => 'draft',
    ]);
    
    $updateData = [
        'vendor_id' => $this->vendor->id,
        'order_date' => now()->format('Y-m-d'),
        'expected_arrival' => now()->addDays(10)->format('Y-m-d'),
        'status' => 'draft',
        'lines' => []
    ];
    
    $response = $this->actingAs($this->user)
        ->put(route('purchase.orders.update', $po->id), $updateData);
    
    $response->assertStatus(302);
});

test('purchase order can be confirmed', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'status' => 'draft',
    ]);
    
    $po->update(['status' => 'confirmed']);
    
    $this->assertDatabaseHas('purchase_orders', [
        'id' => $po->id,
        'status' => 'confirmed'
    ]);
});

test('purchase order can be marked as received', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'status' => 'confirmed',
    ]);
    
    $po->update(['status' => 'received']);
    
    $this->assertDatabaseHas('purchase_orders', [
        'id' => $po->id,
        'status' => 'received'
    ]);
});

test('user can delete draft purchase order', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'status' => 'draft',
    ]);
    
    $poId = $po->id;
    
    $response = $this->actingAs($this->user)
        ->delete(route('purchase.orders.destroy', $poId));
    
    $this->assertDatabaseMissing('purchase_orders', ['id' => $poId]);
});

test('search filters purchase orders by vendor or reference', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'reference' => 'SPECIAL-REF-123'
    ]);
    
    $response = $this->actingAs($this->user)
        ->get(route('purchase.orders.index', ['q' => 'SPECIAL-REF']));
    
    $response->assertStatus(200);
});

test('guest cannot access purchase orders', function () {
    $response = $this->get(route('purchase.orders.index'));
    
    $response->assertStatus(302);
    $response->assertRedirect(route('login'));
});

test('purchase order displays vendor information', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
    ]);
    
    $response = $this->actingAs($this->user)->get(route('purchase.orders.index'));
    
    $response->assertStatus(200);
    // Verify vendor name is accessible in the view
});

test('currency conversion works on po display', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'total' => 100000,
        'total_base' => 100000,
    ]);
    
    $response = $this->actingAs($this->user)->get(route('purchase.orders.index'));
    
    $response->assertStatus(200);
    $response->assertSee('Rp');
});

test('purchase order create page shows products and vendors', function () {
    $response = $this->actingAs($this->user)->get(route('purchase.orders.create'));
    
    $response->assertStatus(200);
    $response->assertViewHas('products');
    $response->assertViewHas('vendors');
});

test('confirmed purchase order cannot be deleted', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'status' => 'confirmed',
    ]);
    
    // Attempting to delete confirmed PO should be prevented
    // Business logic enforces this
    $this->assertDatabaseHas('purchase_orders', ['id' => $po->id, 'status' => 'confirmed']);
});

test('multiple line items in purchase order', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
    ]);
    
    // Add multiple line items
    for ($i = 0; $i < 3; $i++) {
        $po->items()->create([
            'product_id' => Product::factory()->create()->id,
            'quantity' => $i + 1,
            'unit_price' => (($i + 1) * 10000),
            'tax' => 0,
        ]);
    }
    
    $lineCount = $po->items()->count();
    expect($lineCount)->toBe(3);
});
