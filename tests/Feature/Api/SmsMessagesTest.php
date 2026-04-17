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
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.phone_number', '+15551234567')
        ->assertJsonPath('data.0.status', 'pending');
});

it('updates an sms message by id using put', function () {
    $user = User::factory()->create();

    $sms = SmsMessage::query()->create([
        'user_id' => $user->id,
        'phone_number' => '+15551234567',
        'message' => 'Hello',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($user);

    $this->putJson("/api/sms-messages/{$sms->id}", [
        'status' => 'sent',
        'external_id' => 'provider-msg-99',
        'phone_number' => '+19999999999',
        'message' => 'Hacked',
    ])->assertOk()
        ->assertJsonPath('id', $sms->id)
        ->assertJsonPath('status', 'sent')
        ->assertJsonPath('external_id', 'provider-msg-99')
        ->assertJsonStructure(['sent_at']);

    $this->assertDatabaseHas('sms_messages', [
        'id' => $sms->id,
        'status' => 'sent',
        'phone_number' => '+15551234567',
        'message' => 'Hello',
        'external_id' => 'provider-msg-99',
    ]);

    expect($sms->fresh()->sent_at)->not->toBeNull();
});

it('marks an sms message as failed without sent_at', function () {
    $user = User::factory()->create();

    $sms = SmsMessage::query()->create([
        'user_id' => $user->id,
        'phone_number' => '+15551234567',
        'message' => 'Hello',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($user);

    $this->putJson("/api/sms-messages/{$sms->id}", [
        'status' => 'failed',
    ])->assertOk()
        ->assertJsonPath('status', 'failed');

    $sms->refresh();
    expect($sms->status)->toBe('failed');
    expect($sms->sent_at)->toBeNull();
});

it('cannot update another users sms message', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $sms = SmsMessage::query()->create([
        'user_id' => $owner->id,
        'phone_number' => '+15551234567',
        'message' => 'Hello',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($other);

    $this->putJson("/api/sms-messages/{$sms->id}")->assertNotFound();
});

it('lists all pending sms messages for admins', function () {
    $admin = User::factory()->admin()->create();
    $customerA = User::factory()->create();
    $customerB = User::factory()->create();

    SmsMessage::query()->create([
        'user_id' => $customerA->id,
        'phone_number' => '+15551111111',
        'message' => 'A',
        'status' => 'pending',
    ]);

    SmsMessage::query()->create([
        'user_id' => $customerB->id,
        'phone_number' => '+15552222222',
        'message' => 'B',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($admin);

    $this->getJson('/api/sms-messages')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('allows admin to update another users pending sms message', function () {
    $admin = User::factory()->admin()->create();
    $owner = User::factory()->create();

    $sms = SmsMessage::query()->create([
        'user_id' => $owner->id,
        'phone_number' => '+15551234567',
        'message' => 'Hello',
        'status' => 'pending',
    ]);

    Sanctum::actingAs($admin);

    $this->putJson("/api/sms-messages/{$sms->id}")
        ->assertOk()
        ->assertJsonPath('status', 'sent');

    $this->assertDatabaseHas('sms_messages', [
        'id' => $sms->id,
        'status' => 'sent',
    ]);
});
