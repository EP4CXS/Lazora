<?php

use App\Models\Product;
use App\Models\SmsMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates an sms notification when customer places an order', function () {
    /** @var User $customer */
    $customer = User::factory()->create([
        'role' => 'customer',
        'phone_number' => '+639661841984',
    ]);

    $product = Product::factory()->create([
        'stock' => 10,
        'is_active' => true,
    ]);

    $this->actingAs($customer);

    $this->post(route('customer.orders.store'), [
        'from_cart' => false,
        'product_id' => $product->id,
        'quantity' => 1,
    ])->assertRedirect();

    $sms = SmsMessage::query()
        ->where('user_id', $customer->id)
        ->where('status', 'pending')
        ->latest('id')
        ->first();

    expect($sms)->not->toBeNull();
    expect($sms->message)->toContain($product->name);
    expect($sms->message)->toContain('Total:');

    $this->assertDatabaseHas('sms_messages', [
        'user_id' => $customer->id,
        'status' => 'pending',
    ]);
});
