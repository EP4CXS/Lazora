<?php

use App\Actions\Fortify\CreateNewUser;
use App\Jobs\SendPhoneOtp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('generates an otp and dispatches a queued job on registration', function () {
    Bus::fake();

    $action = new CreateNewUser;

    $user = $action->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_number' => '+639661841984',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect($user->phone_otp_hash)->not->toBeNull();
    expect($user->phone_otp_expires_at)->not->toBeNull();
    expect($user->phone_verified_at)->toBeNull();

    Bus::assertDispatched(SendPhoneOtp::class);
});

it('verifies phone otp and marks the phone as verified', function () {
    /** @var User $user */
    $user = User::factory()->create([
        'phone_number' => '+639661841984',
        'phone_verified_at' => null,
        'phone_otp_hash' => Hash::make('123456'),
        'phone_otp_expires_at' => now()->addMinutes(5),
        'phone_otp_attempts' => 0,
    ]);

    $this->actingAs($user)
        ->post(route('phone-otp.verify'), [
            'otp' => '123456',
        ])->assertRedirect(route('dashboard'));

    expect($user->fresh()->phone_verified_at)->not->toBeNull();
});
