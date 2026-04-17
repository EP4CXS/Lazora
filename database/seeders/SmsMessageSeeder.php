<?php

namespace Database\Seeders;

use App\Models\SmsMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class SmsMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        $userIds = User::query()->pluck('id');

        $samples = [
            ['message' => 'Order received. We are preparing your package.', 'status' => 'pending'],
            ['message' => 'Your order is on the way. Track it in your account.', 'status' => 'sent'],
            ['message' => 'Delivery attempt failed. Please confirm your address.', 'status' => 'failed'],
            ['message' => 'OTP: 482193. Do not share this code with anyone.', 'status' => 'sent'],
            ['message' => 'Thanks for shopping! Reply STOP to unsubscribe.', 'status' => 'pending'],
        ];

        foreach (range(1, 50) as $i) {
            $sample = $samples[($i - 1) % count($samples)];

            SmsMessage::query()->create([
                'user_id' => $userIds->random(),
                'phone_number' => '+639661841984',
                'message' => fake()->randomElement([
                    $sample['message'],
                    fake()->realTextBetween(20, 140),
                ]),
                'status' => $sample['status'],
            ]);
        }
    }
}
