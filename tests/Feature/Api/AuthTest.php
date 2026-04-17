<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('logs in and returns a bearer token', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret-password'),
    ]);

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'secret-password',
        'device_name' => 'tests',
    ])->assertOk()
        ->assertJsonStructure([
            'token',
            'token_type',
            'user' => ['id', 'name', 'email', 'role'],
        ])
        ->assertJsonPath('token_type', 'Bearer');

    $this->assertDatabaseCount('personal_access_tokens', 1);
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret-password'),
    ]);

    $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong',
    ])->assertStatus(401);
});

it('logs out and revokes the current token', function () {
    $user = User::factory()->create();

    $plainTextToken = $user->createToken('tests')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$plainTextToken}")
        ->postJson('/api/logout')
        ->assertNoContent();

    $this->assertDatabaseCount('personal_access_tokens', 0);
});
