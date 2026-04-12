<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view products index', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.products.index'));

    $response->assertOk();
});

test('admin can create a product', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.products.store'), [
        'name' => 'Test Keyboard',
        'slug' => 'test-keyboard',
        'category' => 'Peripherals',
        'description' => 'Mechanical switches.',
        'price' => '129.99',
        'stock' => 25,
        'rating' => '4.5',
        'is_featured' => '0',
        'is_active' => '1',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('products', [
        'slug' => 'test-keyboard',
        'name' => 'Test Keyboard',
    ]);
});

test('non-admin cannot access admin products', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->get(route('admin.products.index'));

    $response->assertRedirect();
});

test('customer catalog only lists active products', function () {
    Product::factory()->create(['name' => 'Visible', 'slug' => 'visible-1', 'is_active' => true]);
    Product::factory()->inactive()->create(['name' => 'Hidden', 'slug' => 'hidden-1']);

    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->get(route('customer.products'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('customer/Products')
        ->has('products.data', 1)
        ->where('products.data.0.name', 'Visible'));
});
