<?php

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer products page lists active catalog items from database', function () {
    Product::factory()->create([
        'name' => 'Listed Product',
        'slug' => 'listed-product',
        'is_active' => true,
        'rating' => 4.25,
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->get(route('customer.products'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('customer/Products')
        ->has('products.data', 1)
        ->where('products.data.0.name', 'Listed Product'));
});

test('customer can add to cart and checkout creates an order', function () {
    $product = Product::factory()->create(['stock' => 5, 'price' => 10.00]);
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)->post(route('customer.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 2,
        'size' => '40',
    ])->assertRedirect();

    $this->assertDatabaseHas('cart_items', [
        'user_id' => $customer->id,
        'product_id' => $product->id,
        'quantity' => 2,
        'size' => '40',
    ]);

    $this->actingAs($customer)->post(route('customer.orders.store'), [
        'from_cart' => true,
    ])->assertRedirect();

    expect(Order::query()->where('user_id', $customer->id)->count())->toBe(1);
    expect(CartItem::query()->where('user_id', $customer->id)->count())->toBe(0);
    expect($product->fresh()->stock)->toBe(3);
});

test('customer cannot add to cart with a size not allowed for the product', function () {
    $product = Product::factory()->create([
        'stock' => 5,
        'sizes' => '40,41,42',
    ]);
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->post(route('customer.cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
            'size' => '99',
        ])
        ->assertSessionHasErrors('size');
});

test('customer cannot view another users order', function () {
    $owner = User::factory()->create(['role' => 'customer']);
    $other = User::factory()->create(['role' => 'customer']);
    $order = Order::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($other)->get(route('customer.orders.show', $order))->assertForbidden();
});

test('legacy cart and tracking URLs redirect to orders hub with tab', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($customer)
        ->get(route('customer.cart.index'))
        ->assertRedirect(route('customer.orders.index', ['tab' => 'cart']));

    $this->actingAs($customer)
        ->get(route('customer.tracking.index'))
        ->assertRedirect(route('customer.orders.index', ['tab' => 'tracking']));
});

test('customer cannot update another users cart line', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $owner = User::factory()->create(['role' => 'customer']);
    $other = User::factory()->create(['role' => 'customer']);
    $line = CartItem::query()->create([
        'user_id' => $owner->id,
        'product_id' => $product->id,
        'size' => '',
        'quantity' => 1,
    ]);

    $this->actingAs($other)->patch(route('customer.cart.update', $line), [
        'quantity' => 5,
    ])->assertForbidden();
});
