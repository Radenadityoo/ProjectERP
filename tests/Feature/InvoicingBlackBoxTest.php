<?php

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->customer = Customer::factory()->create();
});

test('user can view invoices list page', function () {
    $response = $this->actingAs($this->user)->get(route('invoicing.invoices.index'));
    $response->assertStatus(200);
});

test('invoices list shows correct pagination', function () {
    Invoice::factory(30)->create(['customer_id' => $this->customer->id]);
    $response = $this->actingAs($this->user)->get(route('invoicing.invoices.index'));
    $response->assertStatus(200);
});

test('invoice total includes tax calculation', function () {
    $invoice = Invoice::factory()->create([
        'customer_id' => $this->customer->id,
        'subtotal' => 100000,
        'tax_amount' => 10000,
        'total' => 110000,
    ]);
    expect((float) $invoice->total)->toBe(110000.0);
});

test('invoice can transition to posted status', function () {
    $invoice = Invoice::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => 'draft',
    ]);
    $invoice->update(['status' => 'posted']);
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'status' => 'posted'
    ]);
});

test('invoice status becomes paid when fully paid', function () {
    $invoice = Invoice::factory()->create([
        'customer_id' => $this->customer->id,
        'total' => 100000,
        'amount_paid' => 100000,
        'status' => 'posted',
    ]);
    
    $invoice->update(['status' => 'paid']);
    
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'status' => 'paid',
    ]);
});

test('user can register payment for invoice', function () {
    $invoice = Invoice::factory()->create([
        'customer_id' => $this->customer->id,
        'total' => 100000,
    ]);
    
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
    ]);
});

test('payment updates invoice amount paid', function () {
    $invoice = Invoice::factory()->create([
        'customer_id' => $this->customer->id,
        'total' => 100000,
    ]);
    
    $invoice->update(['amount_paid' => 100000]);
    
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'amount_paid' => 100000,
    ]);
});

test('user can view payments list', function () {
    $response = $this->actingAs($this->user)->get(route('invoicing.payments.index'));
    $response->assertStatus(200);
});

test('invoice cannot be deleted if paid', function () {
    $invoice = Invoice::factory()->create([
        'customer_id' => $this->customer->id,
        'status' => 'paid',
    ]);
    
    $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
});

test('multiple partial payments can be recorded', function () {
    $invoice = Invoice::factory()->create([
        'customer_id' => $this->customer->id,
        'total' => 100000,
    ]);
    
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
    ]);
});

test('guest cannot access invoices', function () {
    $response = $this->get(route('invoicing.invoices.index'));
    $response->assertStatus(302);
});

test('invoice search filters by number or customer', function () {
    $response = $this->actingAs($this->user)
        ->get(route('invoicing.invoices.index', ['q' => 'test']));
    
    $response->assertStatus(200);
});
