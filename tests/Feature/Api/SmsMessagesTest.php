<?php

use App\Models\SmsMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('requires sanctum authentication', function () {
    $this->getJson('/api/sms-messages')->assertUnauthorized();
});

it('lists sms messages for the authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    SmsMessage::query()->create([
        'user_id' => $user->id,
        'phone_number' => '+15551234567',
        'message' => 'Hello',
        'status' => 'pending',
    ]);

    SmsMessage::query()->create([
        'user_id' => $otherUser->id,
        'phone_number' => '+15559876543',
        'message' => 'Other user pending',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($user);

    $this->getJson('/api/sms-messages')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.status', 'pending')
        ->assertJsonPath('data.1.status', 'pending');
});

it('updates an sms message by id using put', function () {
    $owner = User::factory()->create();
    $apiUser = User::factory()->create();

    $sms = SmsMessage::query()->create([
        'user_id' => $owner->id,
        'phone_number' => '+15551234567',
        'message' => 'Hello',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($apiUser);

    $this->putJson("/api/sms-messages/{$sms->id}", [
        'status' => 'sent',
        'phone_number' => '+19999999999',
        'message' => 'Hacked',
    ])->assertOk()
        ->assertJsonFragment([
            'id' => $sms->id,
            'status' => 'sent',
        ]);

    $this->assertDatabaseHas('sms_messages', [
        'id' => $sms->id,
        'status' => 'sent',
        'phone_number' => '+15551234567',
        'message' => 'Hello',
    ]);
});
