<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'category_id' => null,
            'title' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'discounted_price' => $this->faker->optional()->randomFloat(2, 10, 200),
            'gender' => $this->faker->numberBetween(0, 1),
            'sales_number' => $this->faker->numberBetween(0, 1000),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
