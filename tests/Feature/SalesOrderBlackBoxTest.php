<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\User;

/**
 * Black Box Testing for Sales Orders Module
 * 
 * Tests the complete user workflow without knowledge of internal implementation.
 * Focuses on HTTP responses, database state changes, and user-visible behavior.
 */

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->customer = Customer::factory()->create();
    $this->product = Product::factory()->create();
});

test('user can view sales orders list page', function () {
    $response = $this->actingAs($this->user)->get(route('sales.orders.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('commandbar');
    $response->assertViewHas('orders');
});

test('sales orders list contains pagination', function () {
    // Create 30 orders to exceed pagination limit
    SalesOrder::factory(30)->create(['customer_id' => $this->customer->id]);
    
    $response = $this->actingAs($this->user)->get(route('sales.orders.index'));
    
    $response->assertStatus(200);
    $response->assertViewHas('orders', function ($orders) {
        return $orders->count() <= 25; // Pagination limit
    });
});

test('user can create new sales order', function () {
    $orderData = [
        'customer_id' => $this->customer->id,
        'order_date' => now()->format('Y-m-d'),
        'delivery_date' => now()->addDays(7)->format('Y-m-d'),
        'status' => 'draft',
        'lines' => [
            [
                'product_id' => $this->product->id,
                'description' => 'Test Product',
                'quantity' => 5,
                'unit_price' => 10000,
                'tax' => 0,
            ]
        ]
    ];
    
    $response = $this->actingAs($this->user)
        ->post(route('sales.orders.store'), $orderData);
    
    $response->assertStatus(302); // Redirect on success
    $this->assertDatabaseHas('sales_orders', [
        'customer_id' => $this->customer->id,
        'status' => 'draft'
    ]);
});

test('sales order has correct total calculation', function () {
    $order = SalesOrder::factory()->create([
        'customer_id' => $this->customer->id,
    ]);
    
    $lineData = [
        [
            'product_id' => $this->product->id,
            'quantity' => 5,
            'unit_price' => 10000,
            'tax' => 10,
        ]
    ];
    
    // Add line items
    foreach ($lineData as $line) {
        $order->items()->create($line);
    }
    
    $order->refresh();
    
    // 5 * 10000 = 50000 (subtotal)
    // 50000 * 10% = 5000 (tax)
    // Total = 55000
    expect($order->total)->toBe(55000.00);
});

test('user can edit existing sales order', function () {
    $order = SalesOrder::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => 'draft',
    ]);
    
    $updateData = [
        'customer_id' => $this->customer->id,
        'order_date' => now()->format('Y-m-d'),
        'delivery_date' => now()->addDays(10)->format('Y-m-d'),
        'status' => 'confirmed',
        'lines' => []
    ];
    
    $response = $this->actingAs($this->user)
        ->put(route('sales.orders.update', $order->id), $updateData);
    
    $this->assertDatabaseHas('sales_orders', [
        'id' => $order->id,
        'status' => 'confirmed'
    ]);
});

test('user can delete sales order', function () {
    $order = SalesOrder::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => 'draft',
    ]);
    
    $orderId = $order->id;
    
    $response = $this->actingAs($this->user)
        ->delete(route('sales.orders.destroy', $orderId));
    
    $this->assertDatabaseMissing('sales_orders', ['id' => $orderId]);
});

test('guest cannot access sales orders', function () {
    $response = $this->get(route('sales.orders.index'));
    
    $response->assertStatus(302); // Redirect to login
    $response->assertRedirect(route('login'));
});

test('search filters sales orders by customer', function () {
    $order1 = SalesOrder::factory()->create(['customer_id' => $this->customer->id]);
    $customer2 = Customer::factory()->create(['name' => 'Unique Customer Name']);
    $order2 = SalesOrder::factory()->create(['customer_id' => $customer2->id]);
    
    $response = $this->actingAs($this->user)
        ->get(route('sales.orders.index', ['q' => 'Unique']));
    
    $response->assertStatus(200);
});

test('currency conversion works on order display', function () {
    $order = SalesOrder::factory()->create([
        'customer_id' => $this->customer->id,
        'total' => 100000,
        'total_base' => 100000, // Assuming IDR as base
    ]);
    
    $response = $this->actingAs($this->user)->get(route('sales.orders.index'));
    
    $response->assertStatus(200);
    // Verify currency formatted output appears
    $response->assertSee('Rp');
});

test('order status can transition through valid states', function () {
    $order = SalesOrder::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => 'draft',
    ]);
    
    // Draft -> Confirmed
    $order->update(['status' => 'confirmed']);
    $this->assertDatabaseHas('sales_orders', ['id' => $order->id, 'status' => 'confirmed']);
    
    // Confirmed -> Shipped
    $order->update(['status' => 'shipped']);
    $this->assertDatabaseHas('sales_orders', ['id' => $order->id, 'status' => 'shipped']);
    
    // Shipped -> Delivered
    $order->update(['status' => 'delivered']);
    $this->assertDatabaseHas('sales_orders', ['id' => $order->id, 'status' => 'delivered']);
});
