<?php

namespace Database\Factories;

use App\Enums\OrderFulfillmentStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.Str::upper(Str::random(10)),
            'status' => OrderFulfillmentStatus::Placed,
            'payment_status' => PaymentStatus::Paid,
            'total' => fake()->randomFloat(2, 25, 750),
            'notes' => null,
        ];
    }
}
