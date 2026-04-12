<?php

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        (new AdminUserSeeder)->run();
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        User::query()
            ->where('email', 'admin@example.com')
            ->where('role', 'admin')
            ->delete();
    }
};
