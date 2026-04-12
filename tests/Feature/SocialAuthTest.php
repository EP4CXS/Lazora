<?php

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

uses(RefreshDatabase::class);

test('guests can redirect to google for authentication', function () {
    $response = $this->get('/auth/google/redirect');

    $response->assertRedirect();
    $this->assertStringContainsString('google', $response->getTargetUrl());
});

test('google redirect without oauth env vars redirects to login with status', function () {
    config([
        'services.google.client_id' => null,
        'services.google.client_secret' => null,
    ]);

    $response = $this->get('/auth/google/redirect');

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status');
});

test('unsupported provider redirects to login', function () {
    $response = $this->get('/auth/facebook/redirect');

    $response->assertRedirect(route('login'));
});

test('new user is created and logged in via google', function () {
    $socialiteUser = mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('123456789');
    $socialiteUser->shouldReceive('getName')->andReturn('John Doe');
    $socialiteUser->shouldReceive('getEmail')->andReturn('john@example.com');
    $socialiteUser->shouldReceive('getToken')->andReturn('google-token');
    $socialiteUser->shouldReceive('getRefreshToken')->andReturn('google-refresh-token');
    $socialiteUser->token = 'google-token';
    $socialiteUser->refreshToken = 'google-refresh-token';

    Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect(config('fortify.home'));
    $this->assertAuthenticated();

    $user = User::where('email', 'john@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->name)->toBe('John Doe');
    expect($user->email_verified_at)->not->toBeNull();
    expect($user->socialAccounts)->toHaveCount(1);
    expect($user->socialAccounts->first()->provider)->toBe('google');
    expect($user->socialAccounts->first()->provider_id)->toBe('123456789');
});

test('existing user with social account is logged in via google', function () {
    $user = User::factory()->create(['email' => 'john@example.com']);
    SocialAccount::create([
        'user_id' => $user->id,
        'provider' => 'google',
        'provider_id' => '123456789',
        'token' => 'old-token',
    ]);

    $socialiteUser = mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('123456789');
    $socialiteUser->shouldReceive('getName')->andReturn('John Doe');
    $socialiteUser->shouldReceive('getEmail')->andReturn('john@example.com');
    $socialiteUser->shouldReceive('getToken')->andReturn('new-token');
    $socialiteUser->shouldReceive('getRefreshToken')->andReturn('new-refresh-token');
    $socialiteUser->token = 'new-token';
    $socialiteUser->refreshToken = 'new-refresh-token';

    Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect(config('fortify.home'));
    $this->assertAuthenticatedAs($user);

    expect($user->fresh()->socialAccounts->first()->token)->toBe('new-token');
});

test('existing user without social account gets social account linked', function () {
    $user = User::factory()->create(['email' => 'john@example.com']);

    $socialiteUser = mock(SocialiteUser::class);
    $socialiteUser->shouldReceive('getId')->andReturn('123456789');
    $socialiteUser->shouldReceive('getName')->andReturn('John Doe');
    $socialiteUser->shouldReceive('getEmail')->andReturn('john@example.com');
    $socialiteUser->shouldReceive('getToken')->andReturn('google-token');
    $socialiteUser->shouldReceive('getRefreshToken')->andReturn('google-refresh-token');
    $socialiteUser->token = 'google-token';
    $socialiteUser->refreshToken = 'google-refresh-token';

    Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect(config('fortify.home'));
    $this->assertAuthenticatedAs($user);

    expect($user->socialAccounts)->toHaveCount(1);
    expect($user->socialAccounts->first()->provider)->toBe('google');
});

test('google callback failure redirects to login with status', function () {
    Socialite::shouldReceive('driver->user')->andThrow(new Exception('Authentication failed'));

    $response = $this->get('/auth/google/callback');

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('unsupported provider callback redirects to login', function () {
    $response = $this->get('/auth/facebook/callback');

    $response->assertRedirect(route('login'));
});
