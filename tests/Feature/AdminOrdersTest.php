<?php

use App\Enums\OrderFulfillmentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin dashboard shows real order counts', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/Dashboard')
        ->where('orderStats.total', 0)
        ->where('orderStats.pendingConfirmation', 0));
});

test('admin orders index lists customer orders', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);
    $product = Product::factory()->create(['stock' => 10, 'price' => 50]);

    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'status' => OrderFulfillmentStatus::Placed,
        'total' => 50,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => $product->price,
        'line_total' => 50,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.orders.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('admin/orders/Index')
        ->has('orders.data', 1)
        ->where('orders.data.0.order_number', $order->order_number));
});

test('admin can confirm a placed order', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);
    $product = Product::factory()->create(['stock' => 10, 'price' => 50]);

    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'status' => OrderFulfillmentStatus::Placed,
        'total' => 50,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'unit_price' => $product->price,
        'line_total' => 50,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.orders.confirm', $order))
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(OrderFulfillmentStatus::Confirmed);
});

test('admin can deny a placed order with reason and restore stock', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);
    $product = Product::factory()->create(['stock' => 8, 'price' => 50]);

    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'status' => OrderFulfillmentStatus::Placed,
        'total' => 50,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'unit_price' => $product->price,
        'line_total' => 100,
    ]);

    $product->update(['stock' => 6]);

    $this->actingAs($admin)
        ->post(route('admin.orders.deny', $order), [
            'reason' => 'Size unavailable for restock this week.',
        ])
        ->assertRedirect();

    $order->refresh();

    expect($order->status)->toBe(OrderFulfillmentStatus::Cancelled);
    expect($order->denial_reason)->toBe('Size unavailable for restock this week.');
    expect($product->fresh()->stock)->toBe(8);
});

test('customer cannot access admin orders', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)->get(route('admin.orders.index'))->assertRedirect();
});
