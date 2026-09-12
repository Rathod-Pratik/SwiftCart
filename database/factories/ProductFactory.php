<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => fake()->word(),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(100, 10000),
            'discount' => fake()->numberBetween(0, 100),
            'stock' => fake()->numberBetween(1, 100),
            'image' => [fake()->imageUrl()],
            'category_id' => Category::factory(),
            'related_products' => null,
            'store_link' => fake()->url(),
            'is_featured' => fake()->boolean(),
            'is_limited' => fake()->boolean(),
            'is_trending' => fake()->boolean(),
            'features' => fake()->words(3, true),
            'vendor_id' => User::factory(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'visibility' => fake()->randomElement(['public', 'private']),
            'about' => [fake()->paragraph()],
            'menifectures_images' => [fake()->imageUrl()],
        ];
    }
}
