<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Demo footwear catalog — images live under public/images/products/.
     *
     * @return list<array<string, mixed>>
     */
    public static function catalog(): array
    {
        return [
            [
                'name' => 'Nebula X-1 Pro',
                'slug' => 'nebula-x1-pro',
                'category' => 'Basketball Shoes',
                'color' => 'Cyan / Magenta / Ultra-Violet',
                'sizes' => 'US Men\'s 7 – 14',
                'description' => 'High-performance basketball silhouette with 3D-molded organic overlays for lockdown support. Dual-density cushioning returns energy on every cut, while the breathable upper keeps airflow moving when the game heats up.',
                'price' => 185.00,
                'stock' => 38,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => '/images/products/nebula-x1-pro.png',
                'rating' => 4.85,
            ],
            [
                'name' => 'AeroCourt Elite — Royal Purple',
                'slug' => 'aerocourt-elite-royal-purple',
                'category' => 'Basketball Shoes',
                'color' => 'Court Purple / Black / Violet',
                'sizes' => 'US Men\'s 7, 8, 8.5, 9, 10, 10.5, 11, 12, 13',
                'description' => 'Engineered mesh upper with a responsive violet traction outsole for precision cuts. Metallic branding and a sculpted midsole deliver a premium on-court feel with everyday wearability.',
                'price' => 185.00,
                'stock' => 24,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => '/images/products/aerocourt-royal-purple.png',
                'rating' => 4.90,
            ],
            [
                'name' => 'AeroCourt Elite — Wheat Edition',
                'slug' => 'aerocourt-elite-wheat-edition',
                'category' => 'Lifestyle Sneakers',
                'color' => 'Wheat / Metallic Gold',
                'sizes' => 'US Men\'s 7, 8, 9, 9.5, 10, 10.5, 11, 12, 13',
                'description' => 'A monochromatic wheat finish with breathable knit and smooth synthetic panels. Cushioned collar, gum traction, and subtle metallic accents bridge court performance with street-ready style.',
                'price' => 145.00,
                'stock' => 15,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => '/images/products/aerocourt-wheat.png',
                'rating' => 4.80,
            ],
            [
                'name' => 'Electric Bloom Pro',
                'slug' => 'electric-bloom-pro',
                'category' => 'Training Shoes',
                'color' => 'Hyper Pink / Arctic Punch / Electric Blue',
                'sizes' => 'US Men\'s 7 – 13',
                'description' => 'Bold gradient upper with low-profile stability for agility drills and fast sessions. Dual-density foam and deep rubber traction keep every move confident indoors or out.',
                'price' => 159.00,
                'stock' => 32,
                'is_featured' => false,
                'is_active' => true,
                'image_url' => '/images/products/electric-bloom-pro.png',
                'rating' => 4.75,
            ],
            [
                'name' => 'AeroStep FlyEase Runner',
                'slug' => 'aerostep-flyease-runner',
                'category' => 'Running Shoes',
                'color' => 'Black / White',
                'sizes' => 'US Men\'s 7, 8, 9, 10, 11, 12',
                'description' => 'Easy-entry FlyEase-inspired lacing for quick on-and-off, paired with responsive foam and a road-ready outsole. A crisp black-and-white palette that works with any training rotation.',
                'price' => 130.00,
                'stock' => 45,
                'is_featured' => true,
                'is_active' => true,
                'image_url' => '/images/products/aerostep-flyease-runner.png',
                'rating' => 4.80,
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::catalog() as $row) {
            Product::updateOrCreate(
                ['slug' => $row['slug']],
                $row
            );
        }
    }
}
