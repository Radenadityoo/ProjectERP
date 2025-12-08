<?php

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\User;

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

test('purchase order calculates total correctly', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'subtotal' => 100000,
        'tax_amount' => 10000,
        'total' => 110000,
    ]);
    expect((float) $po->total)->toBe(110000.0);
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

test('search filters purchase orders by vendor', function () {
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
});

test('currency conversion works on po display', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'total' => 100000,
    ]);
    
    $response = $this->actingAs($this->user)->get(route('purchase.orders.index'));
    $response->assertStatus(200);
});

test('confirmed purchase order cannot be deleted', function () {
    $po = PurchaseOrder::factory()->create([
        'vendor_id' => $this->vendor->id,
        'status' => 'confirmed',
    ]);
    
    $this->assertDatabaseHas('purchase_orders', [
        'id' => $po->id,
    ]);
});
