<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Default admin for local/demo. Set ADMIN_INITIAL_PASSWORD in production (e.g. Laravel Cloud).
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email_verified_at' => now(),
                'password' => env('ADMIN_INITIAL_PASSWORD', 'password'),
                'role' => 'admin',
            ]
        );
    }
}
