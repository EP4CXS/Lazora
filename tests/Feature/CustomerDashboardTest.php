<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('customer dashboard renders catalog props from the product service', function () {
    Product::factory()->create([
        'name' => 'Spotlight Shoe',
        'slug' => 'spotlight-shoe',
        'is_active' => true,
        'is_featured' => true,
        'stock' => 10,
        'rating' => 4.9,
    ]);

    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->get(route('customer.dashboard'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('customer/Dashboard')
        ->has('spotlight')
        ->where('spotlight.name', 'Spotlight Shoe')
        ->has('topPicks')
        ->has('trending')
        ->has('highestRated')
        ->has('recentProducts'));
});
