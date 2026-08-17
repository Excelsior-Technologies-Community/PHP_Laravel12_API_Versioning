<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(12),
            'price' => fake()->randomFloat(2, 10, 999),
            'sku' => 'PRD-'.fake()->unique()->numerify('####'),
            'stock' => fake()->numberBetween(0, 500),
            'category' => fake()->randomElement(['Electronics', 'Books', 'Clothing', 'Home', 'Toys']),
            'is_active' => fake()->boolean(85),
        ];
    }
}
