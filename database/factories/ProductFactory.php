<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true).' '.fake()->randomElement(['Pro', 'Lite', 'Max', 'Air', '']);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'category' => fake()->randomElement(['Running Shoes', 'Basketball Shoes', 'Lifestyle Sneakers', 'Training Shoes']),
            'color' => fake()->optional()->randomElement(['Black / White', 'Navy / Silver', 'Triple Black']),
            'sizes' => '37,38,39,40,41,42,43',
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 9.99, 2499.99),
            'stock' => fake()->numberBetween(0, 200),
            'is_featured' => fake()->boolean(20),
            'is_active' => true,
            'image_url' => null,
            'rating' => fake()->randomFloat(2, 3.5, 5),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
