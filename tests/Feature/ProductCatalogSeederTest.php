<?php

use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

test('product seeder creates five footwear demo products with public images', function () {
    $this->seed(ProductSeeder::class);

    expect(Product::query()->where('is_active', true)->count())->toBe(5);

    $slugs = [
        'nebula-x1-pro',
        'aerocourt-elite-royal-purple',
        'aerocourt-elite-wheat-edition',
        'electric-bloom-pro',
        'aerostep-flyease-runner',
    ];

    foreach ($slugs as $slug) {
        $product = Product::query()->where('slug', $slug)->first();

        expect($product)->not->toBeNull()
            ->and($product->image_url)->toStartWith('/images/products/')
            ->and(File::exists(public_path(ltrim((string) $product->image_url, '/'))))->toBeTrue();
    }
});
