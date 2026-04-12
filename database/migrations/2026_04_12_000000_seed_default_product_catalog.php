<?php

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Demo catalog rows — kept in sync with {@see ProductSeeder}.
     */
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        (new ProductSeeder)->run();
    }

    public function down(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        Product::query()
            ->whereIn('slug', array_column(ProductSeeder::catalog(), 'slug'))
            ->delete();
    }
};
