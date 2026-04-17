<?php

namespace Database\Factories;

use App\Models\SmsMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SmsMessage>
 */
class SmsMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $existingUserId = User::query()->inRandomOrder()->value('id');

        return [
            'user_id' => $existingUserId ?? User::factory(),
            'phone_number' => $this->faker->e164PhoneNumber(),
            'message' => $this->faker->realTextBetween(20, 160),
            'status' => $this->faker->randomElement(['pending', 'sent', 'failed']),
        ];
    }
}
