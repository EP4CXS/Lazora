<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin users can access the admin dashboard', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($user)->get('/admin/dashboard');

    $response->assertOk();
});

test('customer users cannot access admin pages', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->get('/admin/dashboard');

    $response->assertRedirect(route('dashboard'));
});

test('customer users can access the customer dashboard', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->get('/customer/dashboard');

    $response->assertOk();
});

test('admin users cannot access customer pages', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($user)->get('/customer/dashboard');

    $response->assertRedirect(route('dashboard'));
});

test('dashboard redirects to role-specific dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);

    $this->actingAs($admin);
    $response = $this->get('/dashboard');
    $response->assertRedirect('/admin/dashboard');

    $this->actingAs($customer);
    $response = $this->get('/dashboard');
    $response->assertRedirect('/customer/dashboard');
});
